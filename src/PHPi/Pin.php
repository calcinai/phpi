<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Exception\InvalidPinModeException;
use Calcinai\PHPi\Peripheral\Register;
use Calcinai\PHPi\Pin\PinFunction;
use Evenement\EventEmitterTrait;

class Pin {

    use EventEmitterTrait;

    const LEVEL_LOW  = 0;
    const LEVEL_HIGH = 1;

    const PULL_NONE  = 0b00;
    const PULL_DOWN  = 0b01;
    const PULL_UP    = 0b10;


    /**
     * @var Board $board
     */
    private $board;

    private $gpio_register;

    /**
     * @var int BCM pin number
     */
    private $pin_number;

    /**
     * @var int function select
     */
    private $function;

    /**
     * In unknown at start, so has to be actively disabled.
     *
     * @var int
     */
    private $pull;

    /**
     * Internal level cache - this will only be the last known level, not actually updated until it changes.
     *
     * @var null|self::LEVEL_LOW|self::LEVEL_HIGH
     */
    private $internal_level;


    private $mask_cache = [];

    public function __construct(Board $board, $pin_number) {
        $this->board = $board;
        $this->gpio_register = $board->getGPIORegister();

        $this->pin_number = $pin_number;

        //This needs to be done since it could be in any state, and the user would never know.
        //Without this could lead to unpredictable behaviour.
        $this->setPull(self::PULL_NONE);

        //Set level cache to actual level
        $this->internal_level = $this->getLevel();

        //set up chained events from the change
        $this->on('change', function($level){
            if($level === self::LEVEL_HIGH){
                $this->emit('high');
            } elseif($level === self::LEVEL_LOW) {
                $this->emit('low');
            }
        });
    }

    public function setFunction($mode) {

        if(is_int($mode)){
            //0 <= $function <= 7
            $this->function = $mode;
        } else {
            $this->function = $this->board->getAltCodeForPinFunction($this->pin_number, $mode);
        }

        list($bank, $mask, $shift) = $this->getAddressMask(3);

        //This feels like its getting messy!  There must be a way to do this with ^=
        $reg = $this->gpio_register[Register\GPIO::$GPFSEL[$bank]];
        $this->gpio_register[Register\GPIO::$GPFSEL[$bank]] = ($reg & ~$mask) | ($this->function << $shift);

        //If it's an input, add it to the edge detect.
        ///TODO - hook this into adding an actual pin ->on() event.
        if($mode === PinFunction::INPUT){
            $this->board->getEdgeDetector()->addPin($this);
        }

        return $this;
    }

    public function getFunction() {
        return $this->function;
    }

    public function high(){
        $this->assertFunction([PinFunction::OUTPUT]);

        $this->internal_level = self::LEVEL_HIGH;

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPSET[$bank]] = $mask;

        return $this;
    }

    public function low(){
        $this->assertFunction([PinFunction::OUTPUT]);

        $this->internal_level = self::LEVEL_LOW;

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPCLR[$bank]] = $mask;

        return $this;
    }

    /**
     * Read the actual pin level from the register
     *
     * @return int
     * @throws InvalidPinModeException
     */
    public function getLevel(){
        $this->assertFunction([PinFunction::INPUT, PinFunction::OUTPUT]);

        list($bank, $mask, $shift) = $this->getAddressMask();
        //Record observed level and return
        $this->internal_level = ($this->gpio_register[Register\GPIO::$GPLEV[$bank]] & $mask) >> $shift;
        return $this->internal_level;
    }

    /**
     * Returns the last observed level of the pin, doesn't actually read it.
     */
    public function getInternalLevel(){
        return $this->internal_level;
    }

    /**
     * @param $direction
     * @return $this
     * @throws InvalidPinModeException
     */
    public function setPull($direction){
        $this->assertFunction([PinFunction::INPUT]);
        $this->pull = $direction;

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::GPPUD] = $this->pull;
        usleep(5); //How long are 150 cycles?
        $this->gpio_register[Register\GPIO::$GPPUDCLK[$bank]] = $mask;
        usleep(5);
        $this->gpio_register[Register\GPIO::$GPPUDCLK[$bank]] = 0;

        return $this;
    }

    public function getPull() {
        return $this->pull;
    }


    public function checkLevel(){

        $level = $this->getLevel();
        if($level !== $this->internal_level) {
            $this->emit('change', [$level]);
        }
    }

    /**
     * Function to check that a pin is in a particular mode before an action is attempted.
     *
     * @param array $valid_functions
     * @return bool
     * @throws InvalidPinModeException
     */
    public function assertFunction(array $valid_functions){
        if(!in_array($this->function, $valid_functions)){
            throw new InvalidPinModeException(sprintf('Pin %s is set to invalid function (%s) for ->%s(). Supported functions are [%s]',
                $this->pin_number,
                $this->function,
                debug_backtrace()[1]['function'],
                implode(',', $valid_functions)));
        }
        return true;
    }


    /**
     * Function to return the bit mask and shift values for a particular number of bits on the current pin.
     *
     * Seems to be a slight gain caching it as a couple of the operations are expensive.
     *
     * @param int $bits
     * @return array[$bank, $mask, $shift]
     */
    public function getAddressMask($bits = 1){

        if(!isset($this->mask_cache[$bits])){
            $divisor = floor(32 / $bits);
            $bank = $this->pin_number / $divisor;
            $shift = ($this->pin_number % $divisor) * $bits;
            $mask = (1 << $bits) - 1 << $shift;

            $this->mask_cache[$bits] = [$bank, $mask, $shift];
        }

        return $this->mask_cache[$bits];
    }


    public function getPinNumber() {
        return $this->pin_number;
    }

}
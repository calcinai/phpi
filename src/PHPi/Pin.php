<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Exception\InvalidPinFunctionException;
use Calcinai\PHPi\Peripheral\Register;
use Calcinai\PHPi\Pin\PinFunction;
use Evenement\EventEmitterTrait;

class Pin {

    use EventEmitterTrait {
        on as traitOn;
    }

    const LEVEL_LOW  = 0;
    const LEVEL_HIGH = 1;

    const PULL_NONE  = 0b00;
    const PULL_DOWN  = 0b01;
    const PULL_UP    = 0b10;


    const EVENT_CHANGE  = 'change';
    const EVENT_HIGH    = 'high';
    const EVENT_LOW     = 'low';


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
        //$this->setPull(self::PULL_NONE); //Maybe it should be up tot he user after all.

        //Set level cache to actual level
        $this->internal_level = $this->getLevel();

        //set up chained events from the change
        $this->on(self::EVENT_CHANGE, function($level){
            if($level === self::LEVEL_HIGH){
                $this->emit(self::EVENT_HIGH);
            } elseif($level === self::LEVEL_LOW) {
                $this->emit(self::EVENT_LOW);
            }
        });
    }

    /**
     * Set the pin function from IN/OUT/ALT0-5
     *
     * @param $function
     * @return $this
     * @throws InvalidPinFunctionException
     */
    public function setFunction($function) {

        if(is_int($function)){
            //0 <= $function <= 7
            $this->function = $function;
        } else {
            $this->function = $this->getAltCodeForPinFunction($function);
        }

        list($bank, $mask, $shift) = $this->getAddressMask(3);

        //This feels like its getting messy!  There must be a way to do this with ^=
        $reg = $this->gpio_register[Register\GPIO::$GPFSEL[$bank]];
        $this->gpio_register[Register\GPIO::$GPFSEL[$bank]] = ($reg & ~$mask) | ($this->function << $shift);

        return $this;
    }

    public function getFunction() {

        //Go get it if it's not set
        //Actually, it is useful to have this update incase it happens from another thread.
        //Leaving the block in place for the time being.
        if(true || !isset($this->function)){
            list($bank, $mask, $shift) = $this->getAddressMask(3);

            $this->function = ($this->gpio_register[Register\GPIO::$GPFSEL[$bank]] & $mask) >> $shift;
        }

        return $this->function;
    }

    public function high(){
        $this->assertFunction([PinFunction::OUTPUT]);

        $this->setInternalLevel(self::LEVEL_HIGH);

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPSET[$bank]] = $mask;

        return $this;
    }

    public function low(){
        $this->assertFunction([PinFunction::OUTPUT]);

        $this->setInternalLevel(self::LEVEL_LOW);

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPCLR[$bank]] = $mask;

        return $this;
    }

    /**
     * Read the actual pin level from the register
     *
     * @return int
     * @throws InvalidPinFunctionException
     */
    public function getLevel(){
        //Can actually be any state
        //$this->assertFunction([PinFunction::INPUT, PinFunction::OUTPUT]);

        list($bank, $mask, $shift) = $this->getAddressMask();
        //Record observed level and return
        $this->setInternalLevel(($this->gpio_register[Register\GPIO::$GPLEV[$bank]] & $mask) >> $shift);
        return $this->getInternalLevel();
    }

    /**
     * Returns the last observed level of the pin, doesn't actually read it.
     */
    public function getInternalLevel(){
        return $this->internal_level;
    }

    /**
     * Check the level against what it's known to be and update it
     * @param $level
     */
    public function setInternalLevel($level){
        if($level !== $this->internal_level) {
            $this->emit(self::EVENT_CHANGE, [$level]);
        }
        $this->internal_level = $level;
    }


    /**
     * @param $direction
     * @return $this
     * @throws InvalidPinFunctionException
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

    /**
     * Overload the trait on() function to hook in an input edge detect.
     *
     * @param $event
     * @param callable $listener
     */
    public function on($event, callable $listener){
        $this->traitOn($event, $listener);

        //If it's an input, add it to the edge detect.
        ///TODO - hook this into adding an actual pin ->on() event.
        if($this->function === PinFunction::INPUT){
            $this->board->getEdgeDetector()->addPin($this);
        }
    }

    /**
     * Function to check that a pin is in a particular mode before an action is attempted.
     *
     * @param array $valid_functions
     * @return bool
     * @throws InvalidPinFunctionException
     */
    public function assertFunction(array $valid_functions){
        if(!in_array($this->function, $valid_functions)){
            throw new InvalidPinFunctionException(sprintf('Pin %s is set to invalid function (%s) for ->%s(). Supported functions are [%s]',
                $this->pin_number,
                $this->function,
                debug_backtrace()[1]['function'],
                implode(',', $valid_functions)));
        }
        return true;
    }


    /**
     * @param $function
     * @return mixed
     * @throws InvalidPinFunctionException
     */
    public function getAltCodeForPinFunction($function){

        $matrix = $this->board->getPinFunctionMatrix();

        if(isset($matrix[$this->pin_number][$function])){
            return $matrix[$this->pin_number][$function];
        }

        throw new InvalidPinFunctionException(sprintf('Pin %s does not support [%s]', $this->pin_number, $function));
    }

    /**
     * @param $alt_code
     * @return mixed
     */
    public function getPinFunctionForAltCode($alt_code){

        $matrix = $this->board->getPinFunctionMatrix();

        //Return null, not false
        return array_search($alt_code, $matrix[$this->pin_number]) ?: null;
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

    public function getBoard(){
        return $this->board;
    }

}
<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Board\AbstractBoard;
use Calcinai\PHPi\Exception\InvalidPinModeException;
use Calcinai\PHPi\Pin\PinFunction;
use Calcinai\PHPi\Peripheral\Register;

use Evenement\EventEmitterTrait;

class Pin {

    use EventEmitterTrait;

    const LEVEL_LOW  = 0;
    const LEVEL_HIGH = 1;

    const PULL_NONE  = 0b00;
    const PULL_DOWN  = 0b01;
    const PULL_UP    = 0b10;


    /**
     * @var AbstractBoard $board
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

    private $mask_cache = [];

    public function __construct(AbstractBoard $board, $pin_number) {
        $this->board = $board;
        $this->gpio_register = $board->getGPIORegister();

        $this->pin_number = $pin_number;

        //This needs to be done since it could be in any state, and the user would never know.
        //Without this could lead to unpredictable behaviour.
        $this->setPull(self::PULL_NONE);
    }

    public function setFunction($mode) {

        if(is_int($mode)){
            //0 <= $function <= 7
            $this->function = $mode;
        } else {
            $this->function = $this->board->getAltCodeForPinFunction($this->pin_number, $mode);
        }

        list($bank, $mask, $shift) = $this->getAddressMask(3);
        $this->gpio_register[Register\GPIO::$GPFSEL[$bank]] = $mask & ($this->function << $shift);

        return $this;
    }

    public function getFunction() {
        return $this->function;
    }

    public function high(){
        $this->assertFunction([PinFunction::OUTPUT]);

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPSET[$bank]] = $mask;

        return $this;
    }

    public function low(){
        $this->assertFunction([PinFunction::OUTPUT]);

        list($bank, $mask) = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPCLR[$bank]] = $mask;

        return $this;
    }

    public function level(){
        $this->assertFunction([PinFunction::INPUT, PinFunction::OUTPUT]);

        list($bank, $mask, $shift) = $this->getAddressMask();
        return ($this->gpio_register[Register\GPIO::$GPLEV[$bank]] & $mask) >> $shift;

    }

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
<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi;

use Calcinai\PHPRPi\Board\AbstractBoard;
use Calcinai\PHPRPi\Exception\InvalidPinModeException;
use Calcinai\PHPRPi\Pin\PinFunction;

class Pin {

    const LEVEL_LOW  = 0;
    const LEVEL_HIGH = 1;

    /**
     * @var AbstractBoard $board
     */
    private $board;

    /**
     * @var int BCM pin number
     */
    private $pin_number;

    /**
     * @var int function select
     */
    private $function;

    private $mask_cache = [];

    public function __construct(AbstractBoard $board, $pin_number) {
        $this->board = $board;
        $this->pin_number = $pin_number;
    }

    public function setFunction($mode) {

        if(is_int($mode)){
            //0 <= $function <= 7
            $this->function = $mode;
        } else {
            $this->function = $this->board->getAltCodeForPinFunction($this->pin_number, $mode);
        }

        $this->board->getGPIORegister()->setFunction($this);
    }

    public function getFunction() {
        return $this->function;
    }

    public function high(){
        $this->assertFunction([PinFunction::OUTPUT]);
        $this->board->getGPIORegister()->setPin($this);
    }

    public function low(){
        $this->assertFunction([PinFunction::OUTPUT]);
        $this->board->getGPIORegister()->clearPin($this);
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
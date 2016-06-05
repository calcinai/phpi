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
     * @param int $bit_multiple
     * @param int $bits_in_bank
     * @return int
     */
    public function getBank($bit_multiple = 1, $bits_in_bank = 32){
        return (int) floor($this->pin_number * $bit_multiple / $bits_in_bank);
    }

    public function getPinBit(){
        return 1 << ($this->pin_number % 32);
    }

    public function getPinNumber() {
        return $this->pin_number;
    }


}
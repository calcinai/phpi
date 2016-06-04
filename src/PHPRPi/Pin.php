<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi;

use Calcinai\PHPRPi\Board\AbstractBoard;
use Calcinai\PHPRPi\Exception\InvalidPinModeException;
use Calcinai\PHPRPi\Pin\Mode;

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
    private $mode;

    public function __construct(AbstractBoard $board, $pin_number) {
        $this->board = $board;
        $this->pin_number = $pin_number;
    }

    public function setMode($mode) {

        if(is_int($mode)){
            //0 <= $function <= 7
            $this->mode = $mode;
        } else {
            $this->mode = $this->board->getAltCodeForPinMode($this->pin_number, $mode);
        }


    }

    public function getMode() {
        return $this->mode;
    }

    public function isOutput(){
    }


    public function high(){
        $this->assertMode([Mode::OUTPUT]);
    }

    public function low(){
        $this->assertMode([Mode::OUTPUT]);

    }


    public function assertMode(array $valid_modes){
        if(!in_array($this->mode, $valid_modes)){
            throw new InvalidPinModeException(sprintf('Pin %s is in invalid mode (%s) for ->%s(). Supported modes are [%s]',
                $this->pin_number,
                $this->mode,
                debug_backtrace()[1]['function'],
                implode(',', $valid_modes)));
        }
        return true;
    }





}
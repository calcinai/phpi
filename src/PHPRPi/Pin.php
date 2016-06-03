<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi;

use Calcinai\PHPRPi\Board\AbstractBoard;

class Pin {

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

    public function setFunction($function) {

        if(is_int($function)){
            //0 <= $function <= 7
            $this->function = $function;
        } else {
            //Parse from matrix
        }


    }

    public function getFunction() {
        return $this->function;
    }
}
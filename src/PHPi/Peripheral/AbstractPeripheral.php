<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board;

class AbstractPeripheral {

    /**
     * @var Board
     */
    protected $board;

    /**
     * @return Board
     */
    public function getBoard(){
        return $this->board;
    }

}
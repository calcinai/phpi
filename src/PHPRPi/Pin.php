<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi;

use Calcinai\PHPRPi\Board\AbstractBoard;

class Pin {

    const

    /**
     * @var AbstractBoard $board
     */
    private $board;

    public function __construct(AbstractBoard $board) {
        $this->board = $board;
    }
}
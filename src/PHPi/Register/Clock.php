<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Register;

use Calcinai\PHPi\Board\AbstractBoard;

class Clock extends AbstractRegister {

    const CNTL  = 40;
    const DIV   = 41;



    const FLIP  = 0x100;
    const BUSY  = 0x080;
    const KILL  = 0x020;
    const ENAB  = 0x010;

    const SRC_MASK = 0xf;

    const SRC_GND    = 0x0;
    const SRC_OSC    = 0x1;
    const SRC_TEST0  = 0x2;
    const SRC_TEST1  = 0x3;
    const SRC_PLLA   = 0x4;
    const SRC_PLLC   = 0x5;
    const SRC_PLLD   = 0x6;
    const SRC_HDMI   = 0x7;


    public static function getOffset() {
        return 0x101000;
    }

    public function __construct(AbstractBoard $board) {
        parent::__construct($board);
    }


}
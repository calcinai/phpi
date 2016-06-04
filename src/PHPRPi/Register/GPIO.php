<?php

/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Register;

class GPIO extends AbstractRegister {

    const GPFSEL_OFFSET   = 0x00;
    const GPSET_OFFSET    = 0x1c;
    const GPCLR_OFFSET    = 0x28;
    const GPLEV_OFFSET    = 0x34;
    const GPEDS_OFFSET    = 0x40;
    const GPREN_OFFSET    = 0x4c;
    const GPFEN_OFFSET    = 0x58;
    const GPHEN_OFFSET    = 0x64;
    const GPLEN_OFFSET    = 0x70;
    const GPAREN_OFFSET   = 0x7c;
    const GPAFEN_OFFSET   = 0x88;
    const GPPUD_OFFSET    = 0x94;
    const GPPUDCLK_OFFSET = 0x98;

    public static function getOffset() {
        return 0x200000;
    }



}
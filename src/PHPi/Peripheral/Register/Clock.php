<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

class Clock extends AbstractRegister {

    const GP0_CTL = 0x1c; //0b0011100
    const GP0_DIV = 0x1d; //0b0011101

    const GP1_CTL = 0x1e; //0b0011110
    const GP1_DIV = 0x1f; //0b0011111

    const GP2_CTL = 0x20; //0b0100000
    const GP2_DIV = 0x21; //0b0100001

    const PCM_CTL = 0x26; //0b0100110
    const PCM_DIV = 0x27; //0b0100111

    const PWM_CTL = 0x28; //0b0101000
    const PWM_DIV = 0x29; //0b0101001


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

}
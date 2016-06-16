<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

class PWM extends AbstractRegister {

    /** Registers */

    const CTL   = 0x0;
    const STA   = 0x4;
    const DMAC  = 0x8;
    const RNG1  = 0x10;
    const DAT1  = 0x14;
    const FIF1  = 0x18;
    const RNG2  = 0x20;
    const DAT2  = 0x24;

    /** Offsets */

    const MSEN1   = 0x0080; // M/S Enable
    const USEF1   = 0x0020; // FIFO
    const POLA1   = 0x0010; // Polarity
    const SBIT1   = 0x0008; // Silence
    const RPTL1   = 0x0004; // Repeat last value if FIFO empty
    const MODE1   = 0x0002; // Run in serial mode
    const PWEN1   = 0x0001; // Channel Enable

    const MSEN2   = 0x8000; // M/S Enable
    const USEF2   = 0x2000; // FIFO
    const POLA2   = 0x1000; // Polarity
    const SBIT2   = 0x0800; // Silence
    const RPTL2   = 0x0400; // Repeat last value if FIFO empty
    const MODE2   = 0x0200; // Run in serial mode
    const PWEN2   = 0x0100; // Channel Enable

    static $DAT = [self::DAT1, self::DAT2];
    static $RNG = [self::RNG1, self::RNG2];

    static $MSEN = [self::MSEN1, self::MSEN2];
    static $POLA = [self::POLA1, self::POLA2];
    static $PWEN = [self::PWEN1, self::PWEN2];

    public static function getOffset() {
        return 0x20C000;
    }

}
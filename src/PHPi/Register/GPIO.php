<?php

/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Register;

use Calcinai\PHPi\Board\AbstractBoard;
use Calcinai\PHPi\Pin;

class GPIO extends AbstractRegister {

    const GPFSEL0   = 0x0000; // GPIO Function Select 0 (R/W)
    const GPFSEL1   = 0x0004; // GPIO Function Select 1 (R/W)
    const GPFSEL2   = 0x0008; // GPIO Function Select 2 (R/W)
    const GPFSEL3   = 0x000C; // GPIO Function Select 3 (R/W)
    const GPFSEL4   = 0x0010; // GPIO Function Select 4 (R/W)
    const GPFSEL5   = 0x0014; // GPIO Function Select 5 (R/W)
    const GPSET0    = 0x001C; // GPIO Pin Output Set 0 (W)
    const GPSET1    = 0x0020; // GPIO Pin Output Set 1 (W)
    const GPCLR0    = 0x0028; // GPIO Pin Output Clear 0 (W)
    const GPCLR1    = 0x002C; // GPIO Pin Output Clear 1 (W)
    const GPLEV0    = 0x0034; // GPIO Pin Level 0 (R)
    const GPLEV1    = 0x0038; // GPIO Pin Level 1 (R)
    const GPEDS0    = 0x0040; // GPIO Pin Event Detect Status 0 (R/W)
    const GPEDS1    = 0x0044; // GPIO Pin Event Detect Status 1 (R/W)
    const GPREN0    = 0x004C; // GPIO Pin Rising Edge Detect Enable 0 (R/W)
    const GPREN1    = 0x0050; // GPIO Pin Rising Edge Detect Enable 1 (R/W)
    const GPFEN0    = 0x0058; // GPIO Pin Falling Edge Detect Enable 0 (R/W)
    const GPFEN1    = 0x005C; // GPIO Pin Falling Edge Detect Enable 1 (R/W)
    const GPHEN0    = 0x0064; // GPIO Pin High Detect Enable 0 (R/W)
    const GPHEN1    = 0x0068; // GPIO Pin High Detect Enable 1 (R/W)
    const GPLEN0    = 0x0070; // GPIO Pin Low Detect Enable 0 (R/W)
    const GPLEN1    = 0x0074; // GPIO Pin Low Detect Enable 1 (R/W)
    const GPAREN0   = 0x007C; // GPIO Pin Async. Rising Edge Detect 0 (R/W)
    const GPAREN1   = 0x0080; // GPIO Pin Async. Rising Edge Detect 1 (R/W)
    const GPAFEN0   = 0x0088; // GPIO Pin Async. Falling Edge Detect 0 (R/W)
    const GPAFEN1   = 0x008C; // GPIO Pin Async. Falling Edge Detect 1 (R/W)
    const GPPUD     = 0x0094; // GPIO Pin Pull-up/down Enable (R/W)
    const GPPUDCLK0 = 0x0098; // GPIO Pin Pull-up/down Enable Clock 0 (R/W)
    const GPPUDCLK1 = 0x009C; // GPIO Pin Pull-up/down Enable Clock 1 (R/W)


    static $GPFSEL = [self::GPFSEL0, self::GPFSEL1, self::GPFSEL2, self::GPFSEL3, self::GPFSEL4, self::GPFSEL5];
    static $GPSET = [self::GPSET0, self::GPSET1];
    static $GPCLR = [self::GPCLR0, self::GPCLR1];

    public static function getOffset() {
        return 0x200000;
    }

    public function __construct(AbstractBoard $board) {
        parent::__construct($board);


    }

    public function setPin(Pin $pin){
        list($bank, $mask) = $pin->getAddressMask();
        $this[static::$GPSET[$bank]] = $mask;
    }

    public function clearPin(Pin $pin){
        list($bank, $mask) = $pin->getAddressMask();
        $this[static::$GPSET[$bank]] = $mask;
    }

    public function setFunction(Pin $pin) {
        list($bank, $mask, $shift) = $pin->getAddressMask(3);
        $this[static::$GPSET[$bank]] = $mask & ($pin->getFunction() << $shift);
    }

}
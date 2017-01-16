<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\Feature\SoC;

trait BCM2837
{
    //From what I understand there's not a lot of difference between the SoC except the CPU
    use BCM2835;

    public static function getPeripheralBaseAddress()
    {
        return 0x3f000000;
    }
}
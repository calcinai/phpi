<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\V3;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class BPlus extends Board
{

    use Feature\SoC\BCM2837;
    use Feature\HDMI;
    use Feature\Ethernet;
    use Feature\Header\J8;

    public static function getBoardName()
    {
        return '3 Model B+';
    }
}
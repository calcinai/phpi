<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\V2;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class B extends Board
{

    use Feature\SoC\BCM2836;
    use Feature\HDMI;
    use Feature\Ethernet;
    use Feature\Header\J8;

    public static function getBoardName()
    {
        return '2 Model B';
    }
}
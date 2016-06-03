<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPRPi\Board\V2;

use Calcinai\PHPRPi\Board\AbstractBoard;
use Calcinai\PHPRPi\Board\Feature;

class B extends AbstractBoard {

    use Feature\SoC\BCM2836;
    use Feature\HDMI;
    use Feature\Ethernet;
}
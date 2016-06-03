<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPRPi\Board\V1;

use Calcinai\PHPRPi\Board\AbstractBoard;
use Calcinai\PHPRPi\Board\Feature;

class BRev2 extends AbstractBoard {

    use Feature\Soc\BCM2835;
    use Feature\HDMI;
    use Feature\Ethernet;
}
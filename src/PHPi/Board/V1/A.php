<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPi\Board\V1;

use Calcinai\PHPi\Board\AbstractBoard;
use Calcinai\PHPi\Board\Feature;

class A extends AbstractBoard {

    use Feature\SoC\BCM2835;
    use Feature\HDMI;

}
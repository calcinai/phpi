<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPRPi\Board\V1;

use Calcinai\PHPRPi\Board\AbstractBoard;
use Calcinai\PHPRPi\Board\Feature;

class A extends AbstractBoard {

    use Feature\SoC\BCM2835;

}
<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPRPi\Board;

use Calcinai\PHPRPi\Board\Feature;

class Zero extends AbstractBoard {

    use Feature\SoC\BCM2835;

}
<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPRPi\Board;

use Calcinai\PHPRPi\Board\Feature;

class ComputeModule extends AbstractBoard {

    use Feature\SoC\BCM2835;

}
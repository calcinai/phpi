<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPi\Board;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class Zero extends Board {

    use Feature\SoC\BCM2835;

}
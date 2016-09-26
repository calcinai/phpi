<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPi\Board;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class ComputeModule extends Board {

    use Feature\SoC\BCM2835;

    public static function getBoardName() {
        return 'Compute Module';
    }
}
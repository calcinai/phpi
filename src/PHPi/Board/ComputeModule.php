<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPi\Board;

use Calcinai\PHPi\Board\Feature;

class ComputeModule extends AbstractBoard {

    use Feature\SoC\BCM2835;

}
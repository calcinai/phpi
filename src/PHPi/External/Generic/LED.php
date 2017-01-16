<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic;

use Calcinai\PHPi\External\Output;
use Calcinai\PHPi\Pin;

class LED extends Output
{

    public function flash($iterations = null, $interval = 1, $duty = 0.5)
    {
        parent::pulse($iterations, $interval, $duty);
    }

}
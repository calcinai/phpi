<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin\EdgeDetector;

use Calcinai\PHPi\Pin;

interface EdgeDetectorInterface {

    const EDGE_NONE     = 'none';
    const EDGE_RISING   = 'rising';
    const EDGE_FALLING  = 'falling';
    const EDGE_BOTH     = 'both';


    public function addPin(Pin $pin);
    public function removePin(Pin $pin);
}
<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic;


abstract class Motor {
    abstract public function forward();
    abstract public function reverse();
    abstract public function stop();
}
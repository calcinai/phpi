<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\ADC\Microchip;

abstract class MCP300x {

    abstract public function getNumChannels();
}
<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\External\ADC\Microchip;

class MCP3004 extends MCP300x {

    public function getNumChannels() {
        return 4;
    }
}
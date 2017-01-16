<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\External\ADC\Microchip;

/**
 * This actually needs fixing becuase there is only one address pin.
 *
 * Class MCP3002
 * @package Calcinai\PHPi\External\ADC\Microchip
 */
class MCP3002 extends MCP300x
{


    public function getNumChannels()
    {
        return 2;
    }
}
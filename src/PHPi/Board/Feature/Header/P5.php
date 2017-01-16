<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\Board\Feature\Header;

trait P5
{

    public function getPhysicalPins()
    {
        return [
            'P5' => [
                //Indexed from real numbers (header starts numbering at 1)
                1 => new PhysicalPin(1, PhysicalPin::SUPPLY_5V),
                2 => new PhysicalPin(2, PhysicalPin::SUPPLY_3V3),
                3 => new PhysicalPin(3, PhysicalPin::GPIO, 28),
                4 => new PhysicalPin(4, PhysicalPin::GPIO, 29),
                5 => new PhysicalPin(5, PhysicalPin::GPIO, 30),
                6 => new PhysicalPin(6, PhysicalPin::GPIO, 31),
                7 => new PhysicalPin(7, PhysicalPin::SUPPLY_GND),
                8 => new PhysicalPin(8, PhysicalPin::SUPPLY_GND)
            ]
        ];
    }
}
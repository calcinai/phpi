<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\Board\Feature\Header;

trait P1
{

    public function getPhysicalPins()
    {
        return [
            'P1' => [
                //Indexed from real numbers (header starts numbering at 1)
                1 => new PhysicalPin(1, PhysicalPin::SUPPLY_3V3),
                2 => new PhysicalPin(2, PhysicalPin::SUPPLY_5V),
                3 => new PhysicalPin(3, PhysicalPin::GPIO, 2),
                4 => new PhysicalPin(4, PhysicalPin::SUPPLY_5V),
                5 => new PhysicalPin(5, PhysicalPin::GPIO, 3),
                6 => new PhysicalPin(6, PhysicalPin::SUPPLY_5V),
                7 => new PhysicalPin(7, PhysicalPin::GPIO, 4),
                8 => new PhysicalPin(8, PhysicalPin::GPIO, 14),
                9 => new PhysicalPin(9, PhysicalPin::SUPPLY_GND),
                10 => new PhysicalPin(10, PhysicalPin::GPIO, 15),
                11 => new PhysicalPin(11, PhysicalPin::GPIO, 17),
                12 => new PhysicalPin(12, PhysicalPin::GPIO, 18),
                13 => new PhysicalPin(13, PhysicalPin::GPIO, 27),
                14 => new PhysicalPin(14, PhysicalPin::SUPPLY_GND),
                15 => new PhysicalPin(15, PhysicalPin::GPIO, 22),
                16 => new PhysicalPin(16, PhysicalPin::GPIO, 23),
                17 => new PhysicalPin(17, PhysicalPin::SUPPLY_3V3),
                18 => new PhysicalPin(18, PhysicalPin::GPIO, 24),
                19 => new PhysicalPin(19, PhysicalPin::GPIO, 10),
                20 => new PhysicalPin(20, PhysicalPin::SUPPLY_GND),
                21 => new PhysicalPin(21, PhysicalPin::GPIO, 9),
                22 => new PhysicalPin(22, PhysicalPin::GPIO, 25),
                23 => new PhysicalPin(23, PhysicalPin::GPIO, 11),
                24 => new PhysicalPin(24, PhysicalPin::GPIO, 8),
                25 => new PhysicalPin(25, PhysicalPin::SUPPLY_GND),
                26 => new PhysicalPin(26, PhysicalPin::GPIO, 7)
            ]
        ];
    }

}
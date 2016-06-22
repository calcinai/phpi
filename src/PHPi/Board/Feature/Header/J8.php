<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\Board\Feature\Header;

trait J8 {

    public function getPhysicalPins(){
        return [
            'J8' => [
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
                26 => new PhysicalPin(26, PhysicalPin::GPIO, 7),
                27 => new PhysicalPin(27, PhysicalPin::ID_SD),
                28 => new PhysicalPin(28, PhysicalPin::ID_SC),
                29 => new PhysicalPin(29, PhysicalPin::GPIO, 5),
                30 => new PhysicalPin(30, PhysicalPin::SUPPLY_GND),
                31 => new PhysicalPin(31, PhysicalPin::GPIO, 6),
                32 => new PhysicalPin(32, PhysicalPin::GPIO, 12),
                33 => new PhysicalPin(33, PhysicalPin::GPIO, 13),
                34 => new PhysicalPin(34, PhysicalPin::SUPPLY_GND),
                35 => new PhysicalPin(35, PhysicalPin::GPIO, 19),
                36 => new PhysicalPin(36, PhysicalPin::GPIO, 16),
                37 => new PhysicalPin(37, PhysicalPin::GPIO, 26),
                38 => new PhysicalPin(38, PhysicalPin::GPIO, 20),
                39 => new PhysicalPin(39, PhysicalPin::SUPPLY_GND),
                40 => new PhysicalPin(40, PhysicalPin::GPIO, 21)
            ]
        ];
    }

}
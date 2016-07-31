<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\Feature\Header;


class PhysicalPin {

    const SUPPLY_3V3 = '3v3';
    const SUPPLY_5V  = '5v';
    const SUPPLY_GND = 'gnd';

    const DNC   = 'dnc';
    const GPIO  = 'gpio';
    const ID_SC = 'id_sc';
    const ID_SD = 'id_sd';


    public $physical_number;
    public $type;

    /**
     * @var int|null
     */
    public $gpio_number;

    public function __construct($physical_number, $type, $gpio_number = null) {

        $this->physical_number = $physical_number;
        $this->type = $type;
        $this->gpio_number = $gpio_number;
    }

}
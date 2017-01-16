<?php

/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic\Motor;

use Calcinai\PHPi\External\Generic\MotorInterface;
use Calcinai\PHPi\Pin;

class HBridge implements MotorInterface
{

    /**
     * @var Pin
     */
    private $pin_a;
    /**
     * @var Pin
     */
    private $pin_b;


    public function __construct(Pin $pin_a, Pin $pin_b)
    {

        $this->pin_a = $pin_a->setFunction(Pin\PinFunction::OUTPUT);
        $this->pin_b = $pin_b->setFunction(Pin\PinFunction::OUTPUT);

        $this->stop();
    }

    public function stop()
    {
        $this->pin_a->low();
        $this->pin_b->low();
    }

    public function forward()
    {
        $this->pin_a->high();
        $this->pin_b->low();
    }

    public function reverse()
    {
        $this->pin_a->low();
        $this->pin_b->high();
    }

}
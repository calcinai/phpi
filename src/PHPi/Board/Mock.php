<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Peripheral\Register;
use React\EventLoop\LoopInterface;

class Mock extends Board
{

    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);

        $this->gpio_register = new Register\Mock($this);
        $this->spi_register = new Register\Mock($this);
        $this->pwm_register = new Register\Mock($this);
        $this->aux_register = new Register\Mock($this);
        $this->clock_register = new Register\Mock($this);

    }

    public static function getPeripheralBaseAddress()
    {
        return 0;
    }

    public static function getPinFunctionMatrix()
    {
        return [];

    }

    public static function getBoardName()
    {
        return 'Mock Board';
    }
}
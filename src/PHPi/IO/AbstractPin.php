<?php

namespace Calcinai\PHPi\IO;

use Calcinai\PHPi\RPi;
use Evenement\EventEmitter;


abstract class AbstractPin extends EventEmitter {

    protected $rpi;
    protected $pin;
    protected $state;

    const STATE_HIGH = 1;
    const STATE_LOW  = 0;

    const DIRECTION_IN  = 'in';
    const DIRECTION_OUT = 'out';

    public function __construct(RPi $rpi, $pin){
        $this->rpi = $rpi;
        $this->pin = $pin;

        $this->setup();
    }

    public function isHigh(){
        return $this->state === self::STATE_HIGH;
    }

    public function isLow(){
        return $this->state === self::STATE_LOW;
    }

    protected abstract function setup();

} 
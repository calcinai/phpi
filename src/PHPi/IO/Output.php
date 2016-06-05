<?php

namespace Calcinai\PHPi\IO;

class Output extends AbstractPin {

    protected function setup(){
        $this->rpi->setupPin($this->pin, self::DIRECTION_OUT);

        //@todo - GPIO lib shold allow both out and in on an 'out' pin
        //$value = $this->gpio->input($this->pin);
        $this->low();
    }

    public function high(){
        $this->state = self::STATE_HIGH;
        $this->rpi->output($this->pin, $this->state);
    }

    public function low(){
        $this->state = self::STATE_LOW;
        $this->rpi->output($this->pin, $this->state);
    }

    public function toggle(){

        if($this->isHigh()){
            $this->low();
        } else {
            $this->high();
        }

    }

    public function pulse($time = 0.5){
        $this->toggle();
        /** @noinspection PhpParamsInspection - Stupid 'numeric' type */
        $this->rpi->addTimer($time, array($this, 'toggle'));
    }

}
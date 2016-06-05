<?php

namespace Calcinai\PHPi\IO;


class Input extends AbstractPin {

    protected function setup(){
        $this->rpi->setupPin($this->pin, self::DIRECTION_IN);
    }

    public function read(){
        $value = intval($this->rpi->input($this->pin));

        if($value === $this->state)
            return false;

        switch($value){
            case self::STATE_HIGH:
                $this->emit('high');
                break;
            case self::STATE_LOW:
                $this->emit('low');
                break;
            default:
                return false;
        }

        $this->emit('change', array($value));
        $this->state = $value;
    }
} 
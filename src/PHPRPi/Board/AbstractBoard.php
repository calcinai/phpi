<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Board;

use Calcinai\PHPRPi\Pin;
use React\EventLoop\LoopInterface;

abstract class AbstractBoard {

    /**
     * @var \React\EventLoop\LibEvLoop|LoopInterface
     */
    private $loop;

    private $serial;

    /**
     * @var Pin[] $pins
     */
    private $pins = [];

    public function __construct(LoopInterface $loop, $serial = null) {
        $this->loop = $loop;
        $this->serial = $serial;
    }

    /**
     * @param $pin_number
     * @return Pin
     */
    public function getPin($pin_number){

        if(!isset($this->pins[$pin_number])){
            $pin = new Pin($this, $pin_number);
            $this->pins[$pin_number] = $pin;
        }

        return $this->pins[$pin_number];
    }

    protected static function getPinMatrix(){
        return [];
    }

}
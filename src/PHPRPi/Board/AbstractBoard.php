<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Board;

use Calcinai\PHPRPi\Exception\InvalidPinModeException;
use Calcinai\PHPRPi\Pin;
use Calcinai\PHPRPi\Register\GPIO;
use React\EventLoop\LoopInterface;

abstract class AbstractBoard {

    /**
     * @var \React\EventLoop\LibEvLoop|LoopInterface
     */
    private $loop;

    /**
     * @var string
     *
     * Pi serial number
     */
    private $serial_number;


    /**
     * @var GPIO
     *
     * Register for gpio functions
     */
    private $register_gpio;

    /**
     * @var Pin[]
     */
    private $pins = [];

    public function __construct(LoopInterface $loop, $serial_number = null) {
        $this->loop = $loop;
        $this->serial_number = $serial_number;


        $this->register_gpio = new GPIO();


    }

    public function getLoop(){
        return $this->loop;
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

    protected static function getPinModeMatrix(){
        return [];
    }

    public function getAltCodeForPinMode($pin_number, $mode){

        $matrix = static::getPinModeMatrix();

        if(isset($matrix[$pin_number][$mode])){
            return $matrix[$pin_number][$mode];
        }

        throw new InvalidPinModeException(sprintf('Pin %s does not support [%s]', $pin_number, $mode));
    }

    abstract public function getPeripheralBaseAddress();

}
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

abstract class AbstractBoard implements BoardInterface {

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
    private $gpio_register;

    /**
     * @var Pin[]
     */
    private $pins = [];

    public function __construct(LoopInterface $loop) {
        $this->loop = $loop;

        $this->gpio_register = new GPIO($this);



//        $this->register_gpio[0x4c] = 0b100000000000000000;
//        $this->register_gpio[0x58] = 0b100000000000000000;
//        $this->register_gpio[0x64] = 0;
//        $this->register_gpio[0x70] = 0;
//        $this->register_gpio[0x7c] = 0;
//        $this->register_gpio[0x88] = 0;
//
//            $loop->addPeriodicTimer(0.01, function(){
//            if($this->register_gpio[0x40] > 0){
//                echo 'press';
//                $this->register_gpio[0x40] = 0b11111111111111111111111111111111;
//                echo decbin($this->register_gpio[0x40]);
//                echo "\n";
//            }
//        });
//


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

    public function getAltCodeForPinFunction($pin_number, $mode){

        $matrix = static::getPinFunctionMatrix();

        if(isset($matrix[$pin_number][$mode])){
            return $matrix[$pin_number][$mode];
        }

        throw new InvalidPinModeException(sprintf('Pin %s does not support [%s]', $pin_number, $mode));
    }

    /**
     * @return GPIO
     */
    public function getGPIORegister() {
        return $this->gpio_register;
    }

}
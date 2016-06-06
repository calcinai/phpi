<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board;

use Calcinai\PHPi\Exception\InvalidPinModeException;
use Calcinai\PHPi\Pin;
use Calcinai\PHPi\PWM;
use Calcinai\PHPi\Register;
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
     * @var Register\GPIO
     *
     * Register for gpio functions
     */
    private $gpio_register;

    /**
     * @var Register\PWM
     */
    private $pwm_register;

    /**
     * @var Register\Clock
     */
    private $clock_register;

    /**
     * @var Pin[]
     */
    private $pins = [];

    /**
     * @var PWM[]
     */
    private $pwms = [];

    public function __construct(LoopInterface $loop) {
        $this->loop = $loop;

        $this->gpio_register = new Register\GPIO($this);
        $this->pwm_register = new Register\PWM($this);
        $this->clock_register = new Register\Clock($this);

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
            $this->pins[$pin_number] = new Pin($this, $pin_number);
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

    public function getPWM($pwm_number){
        if(!isset($this->pwms[$pwm_number])){
            $this->pwms[$pwm_number] = new PWM($this, $pwm_number);
        }

        return $this->pwms[$pwm_number];
    }

    /**
     * @return Register\GPIO
     */
    public function getGPIORegister() {
        return $this->gpio_register;
    }

    /**
     * @return Register\PWM
     */
    public function getPWMRegister() {
        return $this->pwm_register;
    }

    /**
     * @return Register\Clock
     */
    public function getClockRegister() {
        return $this->clock_register;
    }

}
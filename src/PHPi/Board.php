<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Board\BoardInterface;
use Calcinai\PHPi\Peripheral\Clock;
use Calcinai\PHPi\Peripheral\PWM;
use Calcinai\PHPi\Peripheral\Register;
use Calcinai\PHPi\Peripheral\SPI;
use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Pin\EdgeDetector;

use React\EventLoop\LoopInterface;

abstract class Board implements BoardInterface {

    /**
     * @var \React\EventLoop\LibEvLoop|LoopInterface
     */
    private $loop;

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
     * @var Register\SPI
     */
    private $spi_register;

    /**
     * @var Register\Auxiliary
     */
    private $aux_register;

    /**
     * @var EdgeDetector\EdgeDetectorInterface
     */
    private $edge_detector;

    /**
     * @var Pin[]
     */
    private $pins = [];

    /**
     * @var PWM[]
     */
    private $pwms = [];

    /**
     * @var Clock[]
     */
    private $clocks = [];

    /**
     * @var SPI[]
     */
    private $spis;


    public function __construct(LoopInterface $loop) {
        $this->loop = $loop;

        $this->gpio_register = new Register\GPIO($this);
        $this->pwm_register = new Register\PWM($this);
        $this->clock_register = new Register\Clock($this);
        $this->spi_register = new Register\SPI($this);
        $this->aux_register = new Register\Auxiliary($this);

        $this->edge_detector = EdgeDetector\Factory::create($this);

    }

    public function __destruct() {
        Pin\SysFS::cleanup();
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

    /**
     * @param $pwm_number
     * @return PWM
     */
    public function getPWM($pwm_number){
        if(!isset($this->pwms[$pwm_number])){
            $this->pwms[$pwm_number] = new PWM($this, $pwm_number);
        }

        return $this->pwms[$pwm_number];
    }

    /**
     * @param $clock_number
     * @return Clock
     */
    public function getClock($clock_number){
        if(!isset($this->clocks[$clock_number])){
            $this->clocks[$clock_number] = new Clock($this, $clock_number);
        }

        return $this->clocks[$clock_number];
    }

    /**
     * @param $spi_number
     * @return SPI
     */
    public function getSPI($spi_number){
        if(!isset($this->spis[$spi_number])){
            $this->spis[$spi_number] = new SPI($this, $spi_number);
        }

        return $this->spis[$spi_number];
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

    /**
     * @return Register\Auxiliary
     */
    public function getAuxRegister() {
        return $this->aux_register;
    }

    /**
     * @return Register\SPI
     */
    public function getSPIRegister() {
        return $this->spi_register;
    }

    public function getEdgeDetector(){
        return $this->edge_detector;
    }

    /**
     * Should be overloaded by frature trait
     */
    public function getPhysicalPins() {
        return [];
    }


    /**
     * Some of this is the same as the factory, but it's a bit more granular.
     *
     * the result doesn't null-fill, so it's probably better to isset() if anything's depended on in code.
     *
     * @return \stdClass
     */
    public static function getMeta(){

        $meta = new \stdClass();

        //Get a whole lot of stuff - parsing them is the same.
        $info = file_get_contents('/proc/cpuinfo').`lscpu`;


        foreach(explode("\n", $info) as $line) {
            //null,null avoid undefined offset.
            list($tag, $value) = explode(':', $line, 2) + [null, null];

            switch(strtolower(trim($tag))){
                case 'revision':
                    $meta->revision = trim($value);
                    break;
                case 'serial':
                    $meta->serial = trim($value);
                    break;
                case 'cpu(s)':
                    $meta->num_cores = trim($value);
                    break;
                case 'model name':
                    $meta->cpu = trim($value);
                    break;
                case 'cpu max mhz':
                    $meta->speed = trim($value);
                    break;
            }
        }

        return $meta;
    }


}
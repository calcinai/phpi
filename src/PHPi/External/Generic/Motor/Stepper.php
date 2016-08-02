<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic\Motor;

use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Exception\InvalidModeException;
use Calcinai\PHPi\External\Generic\MotorInterface;
use React\EventLoop\Timer\TimerInterface;

class Stepper implements MotorInterface {

    /**
     * @var Pin[]
     */
    private $phases;


    /**
     * Bit pattern for the sequence
     *
     * @var int
     */
    private $mask;

    /**
     * Bit offset between phases
     *
     * @var int
     */
    private $offset;

    /**
     * Number of bits in the sequence
     *
     * @var int
     */
    private $phase_msb;

    private $direction;


    /**
     * Steps per second
     *
     * @var int
     */
    private $speed;

    /**
     * Period in seconds per step
     *
     * @var int
     */
    private $period;


    /**
     * Time in seconds for the motor to reach full speed
     *
     * @var int
     */
    private $acceleration;


    const DEFAULT_SPEED = 25;

    const MODE_WAVE = 0;
    const MODE_FULL = 1;
    const MODE_HALF = 2;

    static $MODE_PATTERNS = [
        self::MODE_WAVE => [0b1000, 1], //offset one bit per phase
        self::MODE_FULL => [0b1100, 1], //offset one bit per phase
        self::MODE_HALF => [0b11100000, 2], //offset two bits per phase
    ];

    /**
     * Timer for autonomous stepping
     *
     * @var TimerInterface
     */
    private $timer;


    /**
     * @param Pin[] $pins
     */
    public function __construct(array $pins) {

        foreach($pins as $pin){
            $this->phases[] = $pin->setFunction(Pin\PinFunction::OUTPUT);
        }

        $this->setMode(self::MODE_FULL);
        $this->setSpeed(self::DEFAULT_SPEED);
    }


    public function setMode($mode){

        if(!isset(static::$MODE_PATTERNS[$mode])){
            throw new InvalidModeException();
        }

        list($this->mask, $this->offset) = static::$MODE_PATTERNS[$mode];

        //Find out how many bits in the pattern.
        //Need MSB high for this to work.
        $this->phase_msb = floor(log($this->mask, 2));

    }

    /**
     * Speed in steps/second
     *
     * @param $speed
     * @return $this
     */
    public function setSpeed($speed){
        $this->speed = $speed;
        $this->period = 1/$speed;
        return $this->stop()->start();
    }

    /**
     * @param $direction
     * @return $this
     */
    public function setDirection($direction){
        $this->direction = $direction;
        return $this;
    }

    /**
     * Seconds until full speed
     *
     * @param $acceleration
     */
    public function setAcceleration($acceleration){
        $this->acceleration = $acceleration;
    }

    public function forward() {
        return $this->setDirection(self::DIRECTION_FORWARD)
            ->start();
    }

    public function reverse() {
        return $this->setDirection(self::DIRECTION_REVERSE)
            ->start();
    }

    public function stop() {

        if($this->isRunning()){
            $this->timer->cancel();
            $this->timer = null;
        }

        return $this;
    }


    public function start(){

        //Seems like the best way to get it.  You're going to have at least a couple of phases.
        $loop = $this->phases[0]->getBoard()->getLoop();

        $this->stop();

        $last_step = $start_time = microtime(true);
        $is_accelerating = $this->acceleration !== null;

        $this->timer = $loop->addPeriodicTimer($this->period, function() use($start_time, &$last_step, &$is_accelerating){

            if($is_accelerating){
                $ratio = (microtime(true) - $start_time) / $this->acceleration;

                if($ratio > 1){
                    $is_accelerating = false;

                } elseif(microtime(true) - $last_step < 1/($ratio * $this->speed)){
                    return;
                } else {
                    $last_step = microtime(true);
                }
            }

            $this->step();
        });

        return $this;
    }


    /**
     * Shift the pattern to the next phase
     * This really is write-only code!
     */
    public function step(){

        //Mask for all bits in sequence
        $bit_mask = ((1 << $this->phase_msb + 1) - 1);

        //Preform a circular shift of the pattern in the appropriate direction
        //This is for the main pattern
        if($this->direction){
            $shifted = $this->mask << 1 | $this->mask >> ($this->phase_msb);
        } else {
            $shifted = $this->mask >> 1 | $this->mask << ($this->phase_msb);
        }

        //Mask off the new 'mask' with however many bits are in the sequence
        $this->mask = $shifted & $bit_mask;

        //Shift all the bits back out to the pins
        foreach($this->phases as $phase) {
            $shifted & 1 ? $phase->high() : $phase->low();
            $shifted >>= $this->offset;
        }

        return $this;
    }

    public function isRunning(){
        return $this->timer instanceof TimerInterface && $this->timer->isActive();
    }

}
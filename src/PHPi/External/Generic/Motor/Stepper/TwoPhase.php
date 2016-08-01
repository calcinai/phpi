<?php

/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\External\Generic\Motor\Stepper;

use Calcinai\PHPi\External\Generic\Motor;
use Calcinai\PHPi\Pin;

class TwoPhase extends Motor {

    const DIRECTION_FORWARD = 'forward';
    const DIRECTION_REVERSE = 'reverse';

    /**
     * @var Pin
     */
    private $phase_1_a;

    /**
     * @var Pin
     */
    private $phase_2_a;

    /**
     * @var Pin
     */
    private $phase_1_b;

    /**
     * @var Pin
     */
    private $phase_2_b;


    private $sequence_position;

    /**
     * TwoPhase constructor.  If the b pins are supplied, they'll just be tied inverse to the a pins
     *
     * Always 4 steps in a cycle
     *
     * @param Pin $phase1_a
     * @param Pin $phase2_a
     * @param Pin|null $phase1_b
     * @param Pin|null $phase2_b
     */
    public function __construct(Pin $phase1_a, Pin $phase2_a, Pin $phase1_b = null, Pin $phase2_b = null) {
        $this->phase_1_a = $phase1_a->setFunction(Pin\PinFunction::OUTPUT);
        $this->phase_1_b = $phase1_b->setFunction(Pin\PinFunction::OUTPUT);
        $this->phase_2_a = $phase2_a->setFunction(Pin\PinFunction::OUTPUT);
        $this->phase_2_b = $phase2_b->setFunction(Pin\PinFunction::OUTPUT);

        $this->sequence_position = 0;
    }


    public function forward() {

    }

    public function reverse() {
        // TODO: Implement reverse() method.
    }

    public function stop() {
        // TODO: Implement stop() method.
    }

    public function step($direction = self::DIRECTION_FORWARD){

        $phase_1 = 3 << 2 & 1 << $this->sequence_position;
        $phase_2 = 3 << 1 & 1 << $this->sequence_position;

        if($phase_1){
            $this->phase_1_a->high();
        } else {
            $this->phase_1_a->low();
        }

        if($phase_2){
            $this->phase_2_a->high();
        } else {
            $this->phase_2_a->low();
        }

        //Probably a more clever way to do this.
        if($direction === self::DIRECTION_FORWARD){
            if($this->sequence_position++ === 3){
                $this->sequence_position = 0;
            }
        } else {
            if($this->sequence_position-- === 0){
                $this->sequence_position = 3;
            }
        }
    }
}
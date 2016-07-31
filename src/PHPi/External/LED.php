<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External;

use Calcinai\PHPi\Pin;
use React\EventLoop\Timer\TimerInterface;

class LED {

    private $pin;
    private $reverse_polarity;

    /**
     * LED constructor.
     * @param Pin $pin
     * @param bool $reverse_polarity
     */
    public function __construct(Pin $pin, $reverse_polarity = false) {
        $this->pin = $pin;
        $this->reverse_polarity = $reverse_polarity;

        $this->pin->setFunction(Pin\PinFunction::OUTPUT);
    }

    public function on(){
        $this->reverse_polarity ? $this->pin->low() : $this->pin->high();
    }

    public function off(){
        $this->reverse_polarity ? $this->pin->high() : $this->pin->low();
    }


    public function flash($iterations = null, $interval = 1, $duty = 0.5){

        $this->on();

        $on_time = $interval * $duty;

        $this->pin->getBoard()
            ->getLoop()->addTimer($on_time, [$this, 'off'])
            ->getLoop()->addPeriodicTimer($interval, function(TimerInterface $timer) use(&$iterations, $on_time) {

            if($iterations !== null && --$iterations === 0){
                $timer->cancel();
                return;
            }

            $this->on();

            $timer->getLoop()->addTimer($on_time, [$this, 'off']);
        });

    }
}
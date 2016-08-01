<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External;

use Calcinai\PHPi\Pin;
use React\EventLoop\Timer\TimerInterface;

class Output {

    /**
     * @var Pin
     */
    protected $pin;

    /**
     * @var bool
     */
    protected $active_high;

    public function __construct(Pin $pin, $active_high = true){
        $this->pin = $pin;
        $this->active_high = $active_high;

        $pin->setFunction(Pin\PinFunction::OUTPUT);
    }


    public function on(){
        $this->active_high ? $this->pin->high() : $this->pin->low();
    }

    public function off(){
        $this->active_high ? $this->pin->low() : $this->pin->high();
    }

    public function toggle(){
        //This will still work if active low
        $this->pin->getLevel() === Pin::LEVEL_HIGH ? $this->pin->low() : $this->pin->high();
    }

    public function pulse($iterations = null, $interval = 1, $duty = 0.5){

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
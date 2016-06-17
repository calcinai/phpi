<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External;

use Calcinai\PHPi\Pin;
use Evenement\EventEmitterTrait;

class Button {

    use EventEmitterTrait;

    private $pin;
    private $loop;
    private $active_high;

    const DEFAULT_HOLD_PERIOD = 1;
    const DEFAULT_PRESS_PERIOD = 0.05;

    const EVENT_PRESS   = 'press';
    const EVENT_HOLD    = 'hold';
    const EVENT_RELEASE = 'release';

    public function __construct(Pin $pin, $active_high = true, $press_period = self::DEFAULT_PRESS_PERIOD, $hold_period = self::DEFAULT_HOLD_PERIOD) {

        $this->pin = $pin;
        $this->loop = $pin->getBoard()->getLoop();
        $this->active_high = $active_high;

        $pin->setFunction(Pin\PinFunction::INPUT);

        //Mainly just connecting up events here

        $press_event = $active_high ? Pin::EVENT_HIGH : Pin::EVENT_LOW;
        $release_event = $active_high ? Pin::EVENT_LOW : Pin::EVENT_HIGH;

        $pin->on($press_event, function() use($pin, $release_event, $press_period, $hold_period) {

            $press_timer = $this->loop->addTimer($press_period, function() {
                $this->emit(self::EVENT_PRESS);
            });

            $hold_timer = $this->loop->addTimer($hold_period, function() {
                $this->emit(self::EVENT_HOLD);
            });

            $pin->once($release_event, function() use(&$press_timer, &$hold_timer){
                $press_timer->cancel();
                $hold_timer->cancel();

                $this->emit(self::EVENT_RELEASE);
            });
        });
    }


}
<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External;


use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Traits\EventEmitterTrait;

class Input
{

    use EventEmitterTrait;

    protected $pin;

    public function __construct(Pin $pin)
    {
        $this->pin = $pin;

        $pin->setFunction(Pin\PinFunction::INPUT);
    }


    public function eventListenerRemoved()
    {
        //Only interested in the last event removed
        if ($this->countListeners() !== 0) {
            return;
        }
        $this->pin->removeAllListeners();
    }


}
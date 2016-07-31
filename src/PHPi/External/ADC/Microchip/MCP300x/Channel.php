<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\ADC\Microchip\MCP300x;

use Calcinai\PHPi\External\ADC\Microchip\MCP300x;
use Calcinai\PHPi\Traits\EventEmitterTrait;
use React\EventLoop\Timer\TimerInterface;

class Channel {

    use EventEmitterTrait;

    const MODE_SINGLE_ENDED = 1;
    const MODE_DIFFERENTIAL = 0;

    const DEFAULT_UPDATE_INTERVAL = 0.04; //25Hz

    const EVENT_CHANGE   = 'change';

    /**
     * @var MCP300x
     */
    private $adc;
    private $channel_number;
    private $mode;

    /**
     * @var TimerInterface
     */
    private $poll_timer;

    private $internal_value;

    public function __construct(MCP300x $adc, $channel_number) {

        $this->adc = $adc;
        $this->channel_number = $channel_number;
        $this->mode = self::MODE_SINGLE_ENDED;

        $this->internal_value = null;
    }

    /**
     * Read a value from the ADC channel and also update internally to fire events
     *
     * @return int
     */
    public function read(){

        $channel_bits = $this->mode << 7 | ($this->channel_number << 4);

        //start bit, channel/diff, padding for read value
        $read_pack = pack('C*', 1, $channel_bits, 0);
        $read = unpack('C/Cmsb/Clsb', $this->adc->transfer($read_pack));

        //10 bits
        $output_mask = (1 << 10) - 1;

        $value = (($read['msb'] << 8) | $read['lsb']) & $output_mask;

        if($this->internal_value !== null && $value !== $this->internal_value){
            $this->emit(self::EVENT_CHANGE, [$value, $this->internal_value]);
        }

        $this->internal_value = $value;
        return $this->internal_value;

    }


    //Meta events for setting up polling
    public function eventListenerAdded(){
        //If it's the first event
        if($this->countListeners(self::EVENT_CHANGE) === 1) {
            $this->poll_timer = $this->adc->getSPI()->getBoard()->getLoop()->addPeriodicTimer(self::DEFAULT_UPDATE_INTERVAL, [$this, 'read']);
        }
    }


    public function eventListenerRemoved(){
        //If it's the last event
        if($this->countListeners(self::EVENT_CHANGE) === 0) {
            $this->poll_timer->cancel();
        }
    }


}
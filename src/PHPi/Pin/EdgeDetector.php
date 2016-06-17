<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Peripheral\Register;

use React\EventLoop\Timer\TimerInterface;

/**
 * Edge detection via polling the status registers. There's not much efficiency gained by reading them as opposed to the individual levels,
 * but it does les us detect up to 1 state change between polls and doesn't require checking the current pin level.
 *
 * Seems to work ok, 4% CPU on Pi3 with triggers on all pins @ 100Hz.
 *
 * Poll frequency can be increased, but will only be useful in some situations - the default frequency will yeild a max delay of 10ms between
 * the event and the handler being called, which for physical applications is more than enough.
 *
 *
 * Class EdgeDetector
 * @package Calcinai\PHPi\Pin
 */
class EdgeDetector {

    /**
     * Should be low enough load to check the registers 100Hz
     *
     * Unfortunately I haven't found a clean way to get the actual interrupts from PHP... yet.
     */
    const DEFAULT_POLL_INTERVAL = 0.01;


    /**
     * @var Board
     */
    private $board;


    /**
     * @var \Calcinai\PHPi\Peripheral\Register\GPIO
     */
    private $gpio_register;


    /**
     * @var TimerInterface
     */
    private $timer;

    /**
     * @var Pin[]
     */
    private $pins;

    private $poll_interval;

    public function __construct(Board $board) {
        $this->board = $board;
        $this->gpio_register = $board->getGPIORegister();
        $this->poll_interval = self::DEFAULT_POLL_INTERVAL;
    }

    public function addPin(Pin $pin){

        /** @noinspection PhpUnusedLocalVariableInspection */
        list($bank, $mask, $shift) = $pin->getAddressMask();
        $this->pins[$bank][$shift] = $pin;

        $this->updateDetectRegisters();
    }

    public function removePin(Pin $pin){

        /** @noinspection PhpUnusedLocalVariableInspection */
        list($bank, $mask, $shift) = $pin->getAddressMask();
        unset($this->pins[$bank][$shift]);

        $this->updateDetectRegisters();
    }

    /**
     * Update the detect registers and start/stop the polling if necessary.
     */
    private function updateDetectRegisters(){

        //Initialise the banks with nothing set
        //Is this a problem? Could something else be depending on them being set in another process?
        $banks_watching = [0, 0];

        foreach($this->pins as $bank => $bank_pins){
            foreach($bank_pins as $bit_position => $pin){
                $banks_watching[$bank] |= 1 << $bit_position;
            }
        }

        ///Set rising and falling - irrelevant events will be ignored.
        foreach($banks_watching as $index => $bank) {
            $this->gpio_register[Register\GPIO::$GPREN[$index]] = $bank;
            $this->gpio_register[Register\GPIO::$GPFEN[$index]] = $bank;
        }

        //Stop or start the timer if necessary.
        //array sum checking if any bits in either bank are set
        if(array_sum($banks_watching) === 0){
            $this->stop();
        } elseif(!$this->isActive()){
            $this->start();
        }

    }

    /**
     * needs to be public so can be called from timer context
     *
     * @return bool
     */
    public function checkStatusRegisters(){
        foreach(Register\GPIO::$GPEDS as $bank => $address){
            $bank_event_bits = $this->gpio_register[$address];

            if($bank_event_bits === 0){
                continue;
            }

            //This used to be a one-liner.  Decided to stop being clever for clarity.
            for($bit_position = 0; $shifted = $bank_event_bits >> $bit_position; $bit_position++){
                //The $bit_position bit is set
                if($shifted & 1){
                    if(isset($this->pins[$bank][$bit_position])){
                        //This is a bit of a double operation here, but really the best that can be done.
                        /** @var Pin $pin */
                        $pin = $this->pins[$bank][$bit_position];
                        //Read pin level, which internally processes it if it's new.

                        //Toggle the pin level since something has changed (even though if we read it now we may have missed it).
                        $pin->setInternalLevel($pin->getInternalLevel() === Pin::LEVEL_HIGH ? Pin::LEVEL_LOW : Pin::LEVEL_HIGH);
                        //Read the level and set it to whatever it is now, it will usually be the toggled state, but if the rise/fall event
                        //was too fast for us to observe, it'll bring the pin back to normal.
                        $pin->getLevel();
                    }

                }
            }

            //Set the clear bit for any that we will have dealt with
            $this->gpio_register[$address] = $bank_event_bits;
        }

    }



    public function isActive(){
        return $this->timer !== null;
    }

    private function start(){

        if($this->isActive()){
            $this->stop();
        }

        $this->timer = $this->board->getLoop()->addPeriodicTimer($this->poll_interval, [$this, 'checkStatusRegisters']);
    }

    private function stop(){
        $this->timer->cancel();
        $this->timer = null;
    }

}
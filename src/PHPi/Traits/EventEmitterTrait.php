<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 *
 * This is based on the Evenement\EventEmitterTrait, but with some more meta functionality since it was getting very messy
 * overloading the trait.
 *
 */
namespace Calcinai\PHPi\Traits;

trait EventEmitterTrait {

    protected $listeners;

    public function on($event, callable $listener){
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][] = $listener;
        $this->eventListenerAdded($event);
    }

    public function once($event, callable $listener){

        $onceListener = function() use (&$onceListener, $event, $listener) {
            $this->removeListener($event, $onceListener);

            call_user_func_array($listener, func_get_args());
        };

        $this->on($event, $onceListener);
    }

    public function removeListener($event, callable $listener){
        if (isset($this->listeners[$event])) {
            if (false !== $index = array_search($listener, $this->listeners[$event], true)) {
                unset($this->listeners[$event][$index]);
                $this->eventListenerRemoved($event);
            }
        }

    }

    public function removeAllListeners($event = null){
        if ($event !== null) {
            foreach($this->listeners($event) as $listener){
                $this->removeListener($event, $listener);
            }
        } else {
            foreach(array_keys($this->listeners) as $event){
                $this->removeAllListeners($event);
            }
        }
    }

    public function listeners($event){
        return isset($this->listeners[$event]) ? $this->listeners[$event] : [];
    }

    public function countListeners($event = null){
        if ($event !== null) {
            return count($this->listeners[$event]);
        } else {
            $num_listeners = 0;
            foreach(array_keys($this->listeners) as $event){
                $num_listeners += count($this->listeners[$event]);
            }
            return $num_listeners;
        }
    }

    public function emit($event, array $arguments = []){

        foreach ($this->listeners($event) as $listener) {
            call_user_func_array($listener, $arguments);
        }
    }

    /**
     * Made these two functions so they don't collide with the events they're describing.
     * Also was getting pretty ugly having no constants to represent the event.
     *
     * @param $event_name
     */
    public function eventListenerAdded($event_name){}

    /**
     * @param $event_name
     */
    public function eventListenerRemoved($event_name){}


}
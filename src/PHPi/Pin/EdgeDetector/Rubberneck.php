<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin\EdgeDetector;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Pin\SysFS;
use Calcinai\Rubberneck\Driver\InotifyWait;
use Calcinai\Rubberneck\Observer;

class Rubberneck implements EdgeDetectorInterface {

    private $board;
    private $observer;

    /**
     * @var Pin[]
     */
    private $pins;

    public function __construct(Board $board) {

        $this->board = $board;
        $this->pins = [];

        $this->observer = new Observer($board->getLoop());
        $this->observer->on(Observer::EVENT_MODIFY, [$this, 'eventDetect']);
        $this->observer->watch(sprintf('%s/gpio*/value', SysFS::PATH_BASE));


    }


    public function addPin(Pin $pin) {
        SysFS::exportPin($pin);
        SysFS::setEdge($pin, self::EDGE_BOTH);

        $this->pins[$pin->getPinNumber()] = $pin;

    }

    public function removePin(Pin $pin) {
        SysFS::unexportPin($pin);

        // TODO: Implement removePin() method.
    }


    public function eventDetect($file){
        list($pin_number) = sscanf($file, SysFS::PATH_BASE.'/gpio%i/');

        $pin = $this->pins[$pin_number];

        //This is the same logic as in the status poll
        //Toggle the pin level since something has changed (even though if we read it now we may have missed it).
        $pin->setInternalLevel($pin->getInternalLevel() === Pin::LEVEL_HIGH ? Pin::LEVEL_LOW : Pin::LEVEL_HIGH);
        //Read the level and set it to whatever it is now, it will usually be the toggled state, but if the rise/fall event
        //was too fast for us to observe, it'll bring the pin back to normal.
        $pin->getLevel();

    }




    /**
     * Only use rubberneck if it can use Inotify, as the sysfs file poll is goign to be slower than the ED SR
     *
     * @return bool
     */
    public static function isSuitable() {
        return InotifyWait::hasDependencies();
    }
}
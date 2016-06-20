<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin\EdgeDetector;

use Calcinai\PHPi\Board;

class Factory {

    public static function create(Board $board){
        //It isn't working correctly yet.
        if (false && Rubberneck::isSuitable()) {
            return new Rubberneck($board);
        } else {
            return new StatusPoll($board);
        }
    }
}
<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board;

use Calcinai\PHPi\Board;

class Mock extends Board {

    public static function getPeripheralBaseAddress() {
        return 0;
    }

    public static function getPinFunctionMatrix() {
        return [];

    }

    public static function getBoardName() {
        return 'Mock Board';
    }
}
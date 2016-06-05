<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board;


interface BoardInterface {
    public static function getPeripheralBaseAddress();
    public static function getPinFunctionMatrix();
}
<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board;


use React\EventLoop\LoopInterface;

interface BoardInterface
{
    public static function getPeripheralBaseAddress();

    public static function getPinFunctionMatrix();

    public static function getBoardName();

    /**
     * @return LoopInterface
     */
    public function getLoop();
}
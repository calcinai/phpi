<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin;

use Calcinai\PHPi\Pin;

class SysFS {

    const PATH_BASE = '/sys/class/gpio';

    static $unexport_on_cleanup = [];

    public static function exportPin(Pin $pin){

        if(self::isExported($pin)){
            return false;
        }

        return (bool) file_put_contents(sprintf('%s/export', self::PATH_BASE), $pin->getPinNumber());
    }


    public static function unexportPin(Pin $pin){

        if(!self::isExported($pin)){
            return false;
        }

        return (bool) file_put_contents(sprintf('%s/unexport', self::PATH_BASE), $pin->getPinNumber());
    }

    public static function getPinValue(Pin $pin){

        if(!self::isExported($pin)){
            return false;
        }

        return file_get_contents(sprintf('%s/gpio%s/value', self::PATH_BASE, $pin->getPinNumber()));
    }

    public static function setEdge(Pin $pin, $edge){

        if(!self::isExported($pin)){
            return false;
        }

        return (bool) file_put_contents(sprintf('%s/gpio%s/edge', self::PATH_BASE, $pin->getPinNumber()), $edge);
    }


    public function isExported(Pin $pin){
        return file_exists(sprintf('%s/gpio%s', self::PATH_BASE, $pin->getPinNumber()));
    }


    public static function cleanup(){
        while($pin = array_pop(static::$unexport_on_cleanup)){
            self::unexportPin($pin);
        }
    }
}
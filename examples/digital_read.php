<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

$pin = $board->getPin(17) //BCM pin number
             ->setFunction(PinFunction::INPUT)
             ->setPull(Pin::PULL_UP);



$loop->addPeriodicTimer($time = 0.1, function() use($loop, $pin, $time){
    var_dump($pin->level());
});



$loop->run();
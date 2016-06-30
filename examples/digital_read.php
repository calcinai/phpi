<?php
/**
 * This example is a crude demonstration of digital inputs
 *
 * Requirements:
 * Any input source that changes the level of BCM pin 17
 *
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
    var_dump($pin->getLevel());
});


$loop->run();
<?php

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

$pin = $board->getPin(4) //BCM pin number
             ->setFunction(PinFunction::OUTPUT);

//Seems to max out at about 4kHz i Pi3
$loop->addPeriodicTimer($time = 1, function() use($loop, $pin, $time){
    $pin->high();

    $loop->addTimer($time / 2, function() use($pin){
        $pin->low();
    });
});

//Benchmark out of event loop - about 15kHz on Pi3
//$start = microtime(true);
//$i = 0;
//while($i++ < 100000){
//    $pin->high()->low();
//}
//
//echo microtime(true) - $start ."\n";

$loop->run();

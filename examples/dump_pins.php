<?php

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

foreach($board->getPhysicalPins() as $physical_number => $pin_info){
    echo $physical_number;
}

$loop->run();

<?php


include __DIR__.'/../vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$rpi = \Calcinai\PHPRPi\Factory::create($loop);

//print_r($rpi);


$loop->run();
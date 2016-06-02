<?php


include __DIR__.'/../vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$rpi = \Calcinai\PHPRPi\Factory::create($loop);

$pin = $rpi->getPin(4);


print_r($rpi);

$loop->run();
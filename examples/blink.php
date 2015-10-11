<?php

use Calcinai\PHPRPi\RPi;

include __DIR__.'/../vendor/autoload.php';


$rpi = new RPi();

print_r($rpi);

inotify_init();


$rpi->getLoop()->run();
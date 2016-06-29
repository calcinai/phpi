<?php

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

$board->getPin(10)->setFunction(PinFunction::SPI0_MOSI);
$board->getPin(9)->setFunction(PinFunction::SPI0_MISO);
$board->getPin(11)->setFunction(PinFunction::SPI0_SCLK);
$board->getPin(8)->setFunction(PinFunction::SPI0_CE0_N);


$spi = $board->getSPI(0);


$spi->write('hi');



$loop->run();

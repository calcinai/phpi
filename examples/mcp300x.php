<?php

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;
use Calcinai\PHPi\External\ADC\Microchip\MCP3004;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

//Flip the appropriate pins to their alt functions.  Will make a helper for this.
$board->getPin(10)->setFunction(PinFunction::SPI0_MOSI);
$board->getPin(9)->setFunction(PinFunction::SPI0_MISO);
$board->getPin(11)->setFunction(PinFunction::SPI0_SCLK);
$board->getPin(8)->setFunction(PinFunction::SPI0_CE0_N);


//This construction should probably change.
$adc = new MCP3004($board->getSPI(0));

print_r($adc);


$loop->run();

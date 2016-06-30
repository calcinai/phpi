<?php

/**
 * This is a basic example of setting up the SPI port
 *
 * Can be tested via loopback by connecting BCM 9 to BCM 10
 */


include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

//Flip the appropriate pins to their alt functions.  Will make a helper for this.
$board->getPin(10)->setFunction(PinFunction::SPI0_MOSI);
$board->getPin(9)->setFunction(PinFunction::SPI0_MISO);
$board->getPin(11)->setFunction(PinFunction::SPI0_SCLK);
$board->getPin(8)->setFunction(PinFunction::SPI0_CE0_N);


$spi = $board->getSPI(0)
    ->chipSelect(0)
    ->setClockSpeed(10000); //~10KHz - will round to a power of 2


//This can be tested by tying MOSI to MISO, will just echo what is sent.
var_dump($spi->transfer('hello'));
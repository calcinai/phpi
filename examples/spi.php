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
    ->setClockSpeed(1e6); //~1MHz - will round to a power of 2


//This can be tested by tying MOSI to MISO, will just echo what is sent.
var_dump($spi->transfer('hello'));


//Benchmark -
// 20kb/s with 1MHz clock and ext-mmap
// 3kb/s with 1MHz without ext-mmap
$start = microtime(true);
$i = 0;
while($i++<100) //1MB
    $spi->transfer(str_repeat('ten  bytes', 100)); //1000 bytes

echo (microtime(true) - $start) ."\n";
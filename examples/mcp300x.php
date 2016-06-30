<?php

/**
 * This example makes use of a MCP300x ADC and converts the 10 bit digital value to a PWM duty and outputs on the led.
 *
 * Requirements:
 * MCP300x attached to SPI0
 * LED connected to BCM pin 18
 *
 */

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

$board->getPin(18)->setFunction(PinFunction::PWM0);

$pwm = $board->getPWM(\Calcinai\PHPi\Peripheral\PWM::PWM0)->start();


//This construction should probably change.
$adc = new MCP3004($board->getSPI(0), 0);

$loop->addPeriodicTimer(0.05, function() use ($adc, $pwm) {
    //Convert to percentage
    $pc = $adc->read($channel = 0) / 10.24;
    $pwm->setDutyCycle($pc);
});


$loop->run();

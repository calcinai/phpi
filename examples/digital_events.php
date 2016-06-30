<?php
/**
 * This example is a crude demonstration of digital inputs and outputs
 *
 * Requirements:
 * Switch pulling BCM pin 17 high
 * LED anode connected to BCM pin 18
 *
 */

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Pin\PinFunction;

$board = \Calcinai\PHPi\Factory::create();

//Switch
$input = $board->getPin(17)
    ->setFunction(PinFunction::INPUT)
    ->setPull(Pin::PULL_UP);

//LED
$output = $board->getPin(18)
    ->setFunction(PinFunction::OUTPUT);


$input->on('high', [$output, 'high']);
$input->on('low', [$output, 'low']);


$board->getLoop()->run();
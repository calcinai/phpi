<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
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


$input->on('high', [$output, 'low']);
$input->on('low', [$output, 'high']);


$board->getLoop()->run();
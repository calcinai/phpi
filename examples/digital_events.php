<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Pin\PinFunction;

$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

//Switch
$input = $board->getPin(17)
    ->setFunction(PinFunction::INPUT)
    ->setPull(Pin::PULL_UP);

//LED
$output = $board->getPin(18)
    ->setFunction(PinFunction::OUTPUT);


$input->on('high', [$output, 'high']);
$input->on('low', [$output, 'low']);


$loop->run();
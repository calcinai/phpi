<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 *
 * This example assumes an hbridge is connected to pins 18 and 19
 */


use Calcinai\PHPi\External\Generic\Motor\Stepper;

include __DIR__.'/../vendor/autoload.php';

$board = \Calcinai\PHPi\Factory::create();

//This is for a 4 phase motor - can be any number of phases
$motor = new Stepper([$board->getPin(5), $board->getPin(6), $board->getPin(13), $board->getPin(19)]);

$board->getLoop()->addPeriodicTimer(0.5, [$motor, 'step']);


$board->getLoop()->run();

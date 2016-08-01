<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 *
 * This example assumes an hbridge is connected to pins 18 and 19
 */


use Calcinai\PHPi\External\Generic\Motor\Stepper\TwoPhase;

include __DIR__.'/../vendor/autoload.php';

$board = \Calcinai\PHPi\Factory::create();

$motor = new TwoPhase($board->getPin(5), $board->getPin(6), $board->getPin(13), $board->getPin(19));

$board->getLoop()->addPeriodicTimer(0.5, [$motor, 'step']);


$board->getLoop()->run();

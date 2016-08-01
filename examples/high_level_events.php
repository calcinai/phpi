<?php
/**
 * This example is a demonstration of high level events
 *
 * Requirements:
 * Switch pulling BCM pin 17 high
 * LED anode connected to BCM pin 18
 * LED anode connected to BCM pin 19
 *
 */

use Calcinai\PHPi\External\Generic\Button;
use Calcinai\PHPi\External\Generic\LED;

include __DIR__.'/../vendor/autoload.php';

$board = \Calcinai\PHPi\Factory::create();

$button = new Button($board->getPin(17));
$press_led = new LED($board->getPin(18));
$hold_led = new LED($board->getPin(19));

$button->on('press', [$press_led, 'on']);
$button->on('hold', [$hold_led, 'on']);

$button->on('release', [$press_led, 'off']);
$button->on('release', [$hold_led, 'off']);

$board->getLoop()->run();
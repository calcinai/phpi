<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\External\Button;
use Calcinai\PHPi\External\LED;

$board = \Calcinai\PHPi\Factory::create();

$button = new Button($board->getPin(17));
$press_led = new LED($board->getPin(18));
$hold_led = new LED($board->getPin(19));

$button->on('press', [$press_led, 'on']);
$button->on('hold', [$hold_led, 'on']);

$button->on('release', [$press_led, 'off']);
$button->on('release', [$hold_led, 'off']);

$board->getLoop()->run();
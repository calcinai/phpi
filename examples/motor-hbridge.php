<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 *
 * This example assumes an hbridge is connected to pins 18 and 19
 */

use Calcinai\PHPi\External\Generic\Motor\HBridge;

include __DIR__.'/../vendor/autoload.php';

$board = \Calcinai\PHPi\Factory::create();

$motor = new HBridge($board->getPin(18), $board->getPin(19));

$motor->forward();
sleep(1);
$motor->reverse();
sleep(1);
$motor->stop();
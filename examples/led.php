<?php

/**
 * This example is an example of primitive external devices (LED)
 *
 * Requirements:
 * LED anode connected to BCM pin 18
 *
 */

use Calcinai\PHPi\External\Generic\LED;

include __DIR__.'/../vendor/autoload.php';

$board = \Calcinai\PHPi\Factory::create();

$led = new LED($board->getPin(18));

//FLash 10 times, once per second, 20% on time
$led->flash(10, 1, 0.2);

$board->getLoop()->run();

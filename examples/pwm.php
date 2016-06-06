<?php

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

$pin = $board->getPin(18) //BCM pin number
             ->setFunction(PinFunction::PWM0);

$pwm = $board->getPWM(0)->start();


/**
 * Everything following is mainly outside the scope of the PWM, it just makes a nice level animation on the output.
 *
 * Not coded for efficiency either!
 */


$loop->addPeriodicTimer($update_interval = 0.01, function() use($pwm){

    $total_time_ms = 1000;
    //Simple sine easing function to calculate the duty
    $pc = -50 * (cos(M_PI * abs($total_time_ms / 2 - (microtime(true) * 1000) % $total_time_ms) / ($total_time_ms / 2)) - 1);

    /** This is the only line relevant to the actual PWM! */
    $pwm->setDutyCycle($pc);
});

$loop->run();

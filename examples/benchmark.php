<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;

$board = \Calcinai\PHPi\Factory::create();

$pin = $board->getPin(18);
$pin->setFunction(PinFunction::OUTPUT);

$start = microtime(true);

for($i=0; $i<1e4; $i++){
    $pin->high(true);
    $pin->low(true);
}

$time = (microtime(true) - $start);
$ms = $time * 1000;
$writes = $i * 2;

$writes_sec = $writes / $time;

printf("%0.2dms for %d transitions (%d writes), %d writes/sec\n", $ms, $i, $writes, $writes_sec);
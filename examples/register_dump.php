<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

include __DIR__ . '/../vendor/autoload.php';

$board = \Calcinai\PHPi\Factory::create();

for ($i = 0; $i < 0x9C; $i += 4) {
    printf("gpio 0x%04x: %032b\n", $i, $board->getGPIORegister()[$i]);
}

for ($i = 0; $i < 0x24; $i += 4) {
    printf("pwm 0x%04x: %032b\n", $i, $board->getPWMRegister()[$i]);
}

for ($i = 0; $i < 0x29; $i += 4) {
    printf("clock 0x%04x: %032b\n", $i, $board->getClockRegister()[$i]);
}

for ($i = 0; $i < 0xD4; $i += 4) {
    printf("aux 0x%04x: %032b\n", $i, $board->getAuxRegister()[$i]);
}


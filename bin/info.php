#!/usr/bin/env php
<?php

//This is a bit.. a lot untidy, but it serves the purpose for now.

include __DIR__ . '/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;

$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

$loop->addPeriodicTimer(0.5, function() use($board){

    //Move cursor back up if it's moved
    static $num_lines = 0;
    if($num_lines) {
        printf("\e[%sA", $num_lines);
    }

    ob_start();


    $meta = $board->getMeta();
    printf(bold("Raspberry Pi %-15s %44s\n"), $meta->board_name, date('r'));
    printf("s/n: %s\n", $meta->serial);
    printf("rev: %s, %sMHz, %d cores\n", $meta->revision, $meta->speed, $meta->num_cores);


    //Dump the headers
    foreach($board->getPhysicalPins() as $header_name => $physical_pins){
        renderHeader($board, $header_name, $physical_pins);
        echo "\n";
    }

    $buffer = ob_get_clean();
    echo $buffer;

    //Record how much was printed
    $num_lines = substr_count($buffer, "\n");
});


$loop->run();


/**
 * @param \Calcinai\PHPi\Board $board
 * @param string $header_name
 * @param Calcinai\PHPi\Board\Feature\Header\PhysicalPin[] $physical_pins
 */
function renderHeader($board, $header_name, $physical_pins){

    $table_format = "| %10s | %5s | %3s | %3s | %s %s | %-3s | %-3s | %-5s | %-10s |\n";

    $high_char = red('●');
    $low_char = grey('●');

    //Ha ha...
    $header = sprintf($table_format, 'func', 'type', 'BCM', 'PHY', $header_name, '', 'PHY', 'BCM', 'type', 'func');
    $hr = preg_replace('/[\w ]/i', '-', str_replace('|', '+', $header));

    echo $hr;
    echo $header;
    echo $hr;

    reset($physical_pins);

    do {
        $odd_pin = current($physical_pins);
        $even_pin = next($physical_pins);

        $odd_pin_attr = getPinAttributes($board, $odd_pin->gpio_number);
        $even_pin_attr = getPinAttributes($board, $even_pin->gpio_number);

        printf($table_format,
            $odd_pin_attr->function, $odd_pin->type, $odd_pin->gpio_number, $odd_pin->physical_number,
            $odd_pin_attr->level ? $high_char : $low_char,
            $even_pin_attr->level ? $high_char : $low_char,
            $even_pin->physical_number, $even_pin->gpio_number, $even_pin->type, $even_pin_attr->function
        );

    } while (next($physical_pins));

    echo $hr;

    printf("%s pin high, %s pin low\n", $high_char, $low_char);

}


/**
 * @param \Calcinai\PHPi\Board $board
 * @param $gpio_number
 * @return stdClass
 */
function getPinAttributes($board, $gpio_number){

    $attributes = new stdClass();

    //So it can be rendered anyway
    if($gpio_number === null){
        $attributes->function = null;
        $attributes->level = null;
    } else {
        $pin = $board->getPin($gpio_number);
        $attributes->function = $pin->getFunctionName();
        $attributes->level = $pin->getLevel();
    }

    return $attributes;
}


function red($text){
    return esc_print('31', $text);
}

function grey($text){
    return esc_print('37', $text);
}

function bold($text){
    return esc_print('1', $text);
}

function esc_print($esc_code, $text){
    return sprintf("\e[%sm%s\e[0m", $esc_code, $text);
}
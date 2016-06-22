<?php

include __DIR__.'/../vendor/autoload.php';

use Calcinai\PHPi\Pin\PinFunction;


$loop = \React\EventLoop\Factory::create();
$board = \Calcinai\PHPi\Factory::create($loop);

foreach($board->getPhysicalPins() as $header_name => $physical_pins){
    renderHeader($board, $header_name, $physical_pins);
}

//$board->getPin(18)->setFunction(PinFunction::OUTPUT);

//$loop->run();


/**
 * @param \Calcinai\PHPi\Board $board
 * @param string $header_name
 * @param Calcinai\PHPi\Board\Feature\Header\PhysicalPin[] $physical_pins
 */
function renderHeader($board, $header_name, $physical_pins){

    $table_format = "| %4s | %5s | %3s | %3s | %s %s | %-3s | %-3s | %-5s | %-4s |\n";

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
            $odd_pin_attr->level ? '●' : '○',
            $even_pin_attr->level ? '●' : '○',
            $even_pin->physical_number, $even_pin->gpio_number, $even_pin->type, $even_pin_attr->function
        );

    } while (next($physical_pins));

    echo $hr;

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

        $function = $pin->getPinFunctionForAltCode($pin->getFunction());
        if($function === null){
            //Means it's in/out
            $function = $pin->getFunction() === PinFunction::INPUT ? 'in' : 'out';
        }

        $attributes->function = $function;
        $attributes->level = $pin->getLevel();
    }

    return $attributes;
}
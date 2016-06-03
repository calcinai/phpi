<?php

include __DIR__.'/../vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$rpi = \Calcinai\PHPRPi\Factory::create($loop);

//$pin = $rpi->getPin(4);

$process = new React\ChildProcess\Process('python -u ../subprocess/mmap-proxy.py /dev/mem 1024 0x3f200000');

$process->on('exit', function($exitCode, $termSignal) {
    echo "Child dead\n";
});

$process->start($loop);

$count = 0;

$loop->addPeriodicTimer($time = 0.1, function() use($process, $loop, $time, &$count){
    $command = 'w';
    $address = 0x1c;
    $value = 0b11111111111111111111111111111111;
    $process->stdin->write($command.pack('cV', $address, $value));

    $loop->addTimer($time / 2, function() use($process, &$count){
        $count++;
        $command = 'w';
        $address = 0x28;
        $value = 0b11111111111111111111111111111111;
        $process->stdin->write($command.pack('cV', $address, $value));
    });

});

$loop->addPeriodicTimer($time = 1, function() use($process, $loop, &$count){
    echo $count."\n";
    $count = 0;
});

$process->stdout->on('data', function($data) {
    echo 'data:';
    var_dump($data);
    $unpacked = unpack('Vvalue', $data);
    var_dump($unpacked['value']);
    var_dump(decbin($unpacked['value']));
});

$process->stderr->on('data', function($data) {
    print_r($data);
});


$loop->run();

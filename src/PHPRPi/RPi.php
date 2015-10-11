<?php

/**
 * @package    php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class RPi {

    /**
     * The loop at the core
     *
     * @var \React\EventLoop\LibEvLoop|LoopInterface
     */
    private $loop;

    public function __construct(LoopInterface $loop = null) {

        //Allow another loop to be passed into the constructor if there's one
        //e.g if this is running a WS server
        if($loop === null){
            $loop = Factory::create();
        }

        $this->loop = $loop;
    }

    public function getLoop() {
        return $this->loop;
    }

}
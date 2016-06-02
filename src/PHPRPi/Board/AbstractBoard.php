<?php

/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Board;

use React\EventLoop\LoopInterface;

abstract class AbstractBoard {

    /**
     * @var \React\EventLoop\LibEvLoop|LoopInterface
     */
    private $loop;

    private $serial;

    public function __construct(LoopInterface $loop, $serial = null) {
        $this->loop = $loop;
        $this->serial = $serial;

        //fopen('mmap:///dev/zero:65536');

    }


}
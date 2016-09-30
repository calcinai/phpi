<?php

namespace Calcinai\PHPi\Tests;


use Calcinai\PHPi\Board;
use Calcinai\PHPi\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    private $board;

    public function setUp()
    {
        $this->board = Factory::create();
    }

    public function testValidBoard()
    {
        $this->assertInstanceOf(Board::class, $this->board);
    }
}

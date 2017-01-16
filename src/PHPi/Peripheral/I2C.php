<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;

use Calcinai\PHPi\Board;

class I2C extends AbstractPeripheral
{

    public function __construct(Board $board)
    {
        throw new \Exception('I2C not implemented');
    }

}
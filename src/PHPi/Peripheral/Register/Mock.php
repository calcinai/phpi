<?php
/**
 * @package    api
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

use Calcinai\PHPi\Board\BoardInterface;

/**
 * Register to store and return data, so testing can be done on non-pi systems
 *
 * Class Mock
 * @package Calcinai\PHPi\Peripheral\Register
 */
class Mock extends AbstractRegister
{

    private $data;

    /**
     * Overload the parent constructor so nothing is actually mapped
     *
     * AbstractRegister constructor.
     * @param BoardInterface $board
     */
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(BoardInterface $board)
    {
        $this->data = [];
    }

    /**
     * @param mixed $offset
     * @return bool
     */

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : 0;
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {

    }

    public static function getOffset()
    {
        return 0;
    }
}
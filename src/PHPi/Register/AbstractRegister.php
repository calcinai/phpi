<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Register;

use Calcinai\PHPi\Board\AbstractBoard;

abstract class AbstractRegister implements RegisterInterface, \ArrayAccess {

    const MMAP_BLOCK_SIZE = 1024;

    private $mmap;

    public function __construct(AbstractBoard $board) {
        $this->mmap = mmap_open('/dev/mem', self::MMAP_BLOCK_SIZE, $board->getPeripheralBaseAddress() + static::getOffset());
        //Should there be a 'register backup' in here that gets replayed on destruct?
    }




    /** ArrayAccess Methods */

    public function offsetExists($offset){
        return $offset < self::MMAP_BLOCK_SIZE;
    }

    public function offsetGet($offset){
        fseek($this->mmap, $offset);
        $unpacked = unpack('Vvalue', fread($this->mmap, 4));

        return $unpacked['value'];
    }

    public function offsetSet($offset, $value){
        fseek($this->mmap, $offset);
        fwrite($this->mmap, pack('V', $value));
    }

    public function offsetUnset($offset){

    }

}
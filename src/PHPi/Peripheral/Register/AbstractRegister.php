<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

use Calcinai\PHPi\Board\AbstractBoard;
use Calcinai\PHPi\Exception\InternalFailureException;

abstract class AbstractRegister implements RegisterInterface, \ArrayAccess {

    /**
     * Apparently this is for 'safety'
     */
    const BCM_PASSWORD = 0x5A000000;

    const MMAP_BLOCK_SIZE = 1024;

    private $mmap;

    public function __construct(AbstractBoard $board) {

        try {
            $this->mmap = mmap_open('/dev/mem', self::MMAP_BLOCK_SIZE, $board->getPeripheralBaseAddress() + static::getOffset());
        } catch (\Exception $e){
            throw new InternalFailureException('Couldn\'t mmap peripheral register, are you running as root?');
        }

        //Only read 4 bytes at a time, not PHP's 8k default
        stream_set_chunk_size($this->mmap, 4);

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
        //printf("Setting 0x%x to %032b\n", $offset, $value); //Useful to know sometimes
        fseek($this->mmap, $offset);
        fwrite($this->mmap, pack('V', $value));
    }

    public function offsetUnset($offset){

    }

}
<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

use Calcinai\PHPi\Board\BoardInterface;
use Calcinai\PHPi\Exception\InternalFailureException;

abstract class AbstractRegister implements RegisterInterface, \ArrayAccess
{

    /**
     * Apparently this is for 'safety'
     */
    const BCM_PASSWORD = 0x5A000000;

    const MMAP_BLOCK_SIZE = 1024;

    private $mmap;

    /**
     * A little bit messy but mainy to give useful errors.  It's probably going to be the most common thing people hit.
     *
     * AbstractRegister constructor.
     * @param BoardInterface $board
     * @throws InternalFailureException
     */
    public function __construct(BoardInterface $board)
    {

        //If there's a direct mapping file, try to use it.
        //Kept this generic in case we eventually get a /dev/spimem, /dev/pwmmem etc
        $dm_file = static::getDirectMemoryFile();
        if ($dm_file !== null && file_exists($dm_file)) {
            $this->mmap = @mmap_open($dm_file, self::MMAP_BLOCK_SIZE, static::getOffset());

            if ($this->mmap === false) {
                $reg_reflect = new \ReflectionClass($this);
                throw new InternalFailureException(sprintf('Couldn\'t map %s register.  You must either run as root, or be a member of the %s group.', $reg_reflect->getShortName(), posix_getgrgid(filegroup($dm_file))['name']));
            }
        } else {
            $this->mmap = @mmap_open('/dev/mem', self::MMAP_BLOCK_SIZE, $board->getPeripheralBaseAddress() + static::getOffset());

            if ($this->mmap === false) {
                $reg_reflect = new \ReflectionClass($this);
                throw new InternalFailureException(sprintf('Couldn\'t map %s register. Are you running as root?', $reg_reflect->getShortName()));
            }
        }

        //Only read 4 bytes at a time, not PHP's 8k default
        stream_set_chunk_size($this->mmap, 4);

        //Should there be a 'register backup' in here that gets replayed on destruct?
    }

    public function __destruct()
    {
        if (is_resource($this->mmap)) {
            fclose($this->mmap);
        }
    }

    /**
     * This is to facilitate registers that have a way of direct (unprivileged) access, eg the /dev/gpiomem
     *
     * @return null
     */
    public static function getDirectMemoryFile()
    {
        return null;
    }


    /** ArrayAccess Methods */

    public function offsetExists($offset)
    {
        return $offset < self::MMAP_BLOCK_SIZE;
    }

    public function offsetGet($offset)
    {
        fseek($this->mmap, $offset);
        $unpacked = unpack('Vvalue', fread($this->mmap, 4));
        return $unpacked['value'];
    }

    public function offsetSet($offset, $value)
    {
        //printf("Setting 0x%x to %032b\n", $offset, $value); //Useful to know sometimes
        fseek($this->mmap, $offset);
        fwrite($this->mmap, pack('V', $value));
    }

    public function offsetUnset($offset)
    {

    }

}
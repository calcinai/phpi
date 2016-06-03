<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Register\Adapter;


use Calcinai\PHPRPi\Board\AbstractBoard;
use Calcinai\PHPRPi\Exception\InternalFailureException;
use React\ChildProcess\Process;

class SubProcess implements AdapterInterface {

    //This should be synchronous since there's theoretically no delay on the instruction.

    private $process;

    const BLOCK_SIZE = 1024;
    const MEM_FILE = '/dev/mem';

    public function __construct(AbstractBoard $board, $file_name, $offset) {

        $this->process = new Process(sprintf('python -u %s %s %d %d', $file_name, self::MEM_FILE, self::BLOCK_SIZE, $offset));

        $this->process->stderr->on('data', function($data) {
            throw new InternalFailureException(sprintf('mmap proxy failed with error:', $data));
        });

        $this->process->start($board->getLoop());
    }

    public function read($address) {
        $this->process->stdin->write('r'.pack('cV', $address, 0));
    }

    public function write($address, $data) {
        $this->process->stdin->write('w'.pack('cV', $address, $data));
    }
}
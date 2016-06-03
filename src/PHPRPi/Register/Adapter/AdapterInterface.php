<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Register\Adapter;


use Calcinai\PHPRPi\Board\AbstractBoard;

interface AdapterInterface {
    public function __construct(AbstractBoard $board, $file_name, $offset);
    public function read($address);
    public function write($address, $data);
}
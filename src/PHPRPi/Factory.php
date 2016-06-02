<?php

/**
 * @package    php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi;

use Calcinai\PHPRPi\Board;
use Calcinai\PHPRPi\Exception\UnsupportedBoardException;
use React\EventLoop\LoopInterface;

class Factory {

    /**
     * @param LoopInterface $loop
     * @return Board\AbstractBoard
     * @throws UnsupportedBoardException
     */
    public static function create(LoopInterface $loop) {

        list($board, $serial) = self::identifyBoard();

        /** @var Board\AbstractBoard $board */
        return new $board($loop, $serial);
    }


    /**
     * @return array, tuple including board class and serial number.
     * @throws UnsupportedBoardException
     */
    private static function identifyBoard() {

        $mock_board_tuple = [Board\Mock::class, null];

        if(!file_exists('/proc/cpuinfo')){
            return $mock_board_tuple;
        }

        $cpuinfo_file = '/proc/cpuinfo';
        $cpuinfo_pattern = '/Hardware\s+:\s(?<soc>.+)\nRevision\s+:\s(?<revision>.+)\nSerial\s+:\s(?<serial>.+)/';

        if(!file_exists($cpuinfo_file) || !preg_match($cpuinfo_pattern, file_get_contents($cpuinfo_file), $matches)){
            //No matches for revision - probably generic Unix
            return $mock_board_tuple;
        }

        $soc = $matches['soc'];
        $revision = $matches['revision'];
        $serial_number = $matches['serial'];

        switch($revision){
            case '0002':
            case '0003':
                return [Board\V1\B::class, $serial_number];
            case '0004':
            case '0005':
            case '0006':
            case '000d':
            case '000e':
            case '000f':
                return [Board\V1\BRev2::class, $serial_number];
            case '0007':
            case '0008':
            case '0009':
                return [Board\V1\A::class, $serial_number];
            case '0010':
                return [Board\V1\BPlus::class, $serial_number];
            case '0011':
                return [Board\ComputeModule::class, $serial_number];
            case '0012':
                return [Board\V1\APlus::class, $serial_number];
            case 'a01041':
            case 'a21041':
                return [Board\V2\B::class, $serial_number];
            case '900092':
                return [Board\Zero::class, $serial_number];
            case 'a02082':
            case 'a22082':
                return [Board\V3\B::class, $serial_number];
        }

        throw new UnsupportedBoardException(sprintf('Board revision [%s/%s] is not (yet) supported.', $revision, $soc));

    }

}

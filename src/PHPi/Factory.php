<?php

/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Exception\UnsupportedBoardException;
use React\EventLoop\LoopInterface;

class Factory {

    /**
     * @param LoopInterface $loop
     * @return Board\AbstractBoard
     * @throws UnsupportedBoardException
     */
    public static function create(LoopInterface $loop) {

        $board = self::identifyBoard();

        /** @var Board\AbstractBoard $board */
        return new $board($loop);
    }


    /**
     * @return array, tuple including board class and serial number.
     * @throws UnsupportedBoardException
     */
    private static function identifyBoard() {

        $cpuinfo_file = '/proc/cpuinfo';
        $cpuinfo_pattern = '/Hardware\s+:\s(?<soc>.+)\nRevision\s+:\s(?<revision>.+)/';

        if(!file_exists($cpuinfo_file) || !preg_match($cpuinfo_pattern, file_get_contents($cpuinfo_file), $matches)){
            //No matches for revision - probably generic Unix
            return Board\Mock::class;
        }

        $soc = $matches['soc'];
        $revision = $matches['revision'];

        switch($revision){
            case '0002':
            case '0003':
                return Board\V1\B::class;
            case '0004':
            case '0005':
            case '0006':
            case '000d':
            case '000e':
            case '000f':
                return Board\V1\BRev2::class;
            case '0007':
            case '0008':
            case '0009':
                return Board\V1\A::class;
            case '0010':
                return Board\V1\BPlus::class;
            case '0011':
                return Board\ComputeModule::class;
            case '0012':
                return Board\V1\APlus::class;
            case 'a01041':
            case 'a21041':
                return Board\V2\B::class;
            case '900092':
                return Board\Zero::class;
            case 'a02082':
            case 'a22082':
                return Board\V3\B::class;
        }

        throw new UnsupportedBoardException(sprintf('Board revision [%s/%s] is not (yet) supported.', $revision, $soc));

    }

}

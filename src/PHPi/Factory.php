<?php

/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Exception\UnsupportedBoardException;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;

class Factory {

    /**
     * This is a little messy now sto allow for php 5.4 (no ::class magic)
     *
     * @param LoopInterface $loop
     * @return \Calcinai\PHPi\Board
     * @throws UnsupportedBoardException
     */
    public static function create(LoopInterface $loop = null) {

        if($loop === null){
            $loop = LoopFactory::create();
        }

        $cpuinfo_file = '/proc/cpuinfo';
        $cpuinfo_pattern = '/Hardware\s+:\s(?<soc>.+)\nRevision\s+:\s(?<revision>.+)/';

        if(!file_exists($cpuinfo_file) || !preg_match($cpuinfo_pattern, file_get_contents($cpuinfo_file), $matches)){
            //No matches for revision - probably generic Unix
            return new Board\Mock($loop);
        }

        $soc = $matches['soc'];
        $revision = $matches['revision'];

        switch($revision){
            case '0002':
            case '0003':
                return new Board\V1\B($loop);
            case '0004':
            case '0005':
            case '0006':
            case '000d':
            case '000e':
            case '000f':
                return new Board\V1\BRev2($loop);
            case '0007':
            case '0008':
            case '0009':
                return new Board\V1\A($loop);
            case '0010':
            case '0013':
                return new Board\V1\BPlus($loop);
            case '0011':
            case '0014':
                return new Board\ComputeModule($loop);
            case '0012':
            case '0015':
                return new Board\V1\APlus($loop);
            case 'a01040':
            case 'a01041':
            case 'a21041':
            case 'a22042':
                return new Board\V2\B($loop);
            case '900092':
            case '900093':
                return new Board\Zero($loop);
            case 'a02082':
            case 'a22082':
                return new Board\V3\B($loop);
        }

        throw new UnsupportedBoardException(sprintf('Board revision [%s/%s] is not (yet) supported.', $revision, $soc));
    }

}

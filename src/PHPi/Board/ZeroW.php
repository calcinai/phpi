<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\PHPi\Board;
use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;
class ZeroW extends Board
{
    use Feature\SoC\BCM2708;
    use Feature\Header\J8;
    public static function getBoardName()
    {
        return 'ZeroW';
    }
}

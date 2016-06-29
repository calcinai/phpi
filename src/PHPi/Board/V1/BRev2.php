<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace  Calcinai\PHPi\Board\V1;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class BRev2 extends Board {

    use Feature\SoC\BCM2835;
    use Feature\HDMI;
    use Feature\Ethernet;

    use Feature\Header\P1, Feature\Header\P5 {
        Feature\Header\P1::getPhysicalPins as getPhysicalPinsP1;
        Feature\Header\P5::getPhysicalPins as getPhysicalPinsP5;
    }

    //All this carry on aliasing traits so you can have more than one while composing from the same data
    public function getPhysicalPins(){
        return array_merge($this->getPhysicalPinsP1(), $this->getPhysicalPinsP5());
    }


    public static function getBoardName() {
        return '1 Model B rev2';
    }

}
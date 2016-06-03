<?php
/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Board\Feature\SoC;

trait BCM2837 {
    //From what I understand there's not a lot of difference between the SoC except the CPU
    use BCM2835;
}
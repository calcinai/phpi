<?php
/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board\AbstractBoard;
use Calcinai\PHPi\Exception\InvalidValueException;

class Clock {

    private $board;

    private $clock_register;

    private $div;
    private $ctl;

    const MIN_FREQUENCY = 0.0001;

    const GP0   = 0;
    const GP1   = 1;
    const GP2   = 2;
    const PCM   = 3;
    const PWM   = 4;

    static $CTL = [
        self::GP0 => Register\Clock::GP0_CTL,
        self::GP1 => Register\Clock::GP1_CTL,
        self::GP2 => Register\Clock::GP2_CTL,
        self::PCM => Register\Clock::PCM_CTL,
        self::PWM => Register\Clock::PWM_CTL
    ];


    static $DIV = [
        self::GP0 => Register\Clock::GP0_DIV,
        self::GP1 => Register\Clock::GP1_DIV,
        self::GP2 => Register\Clock::GP2_DIV,
        self::PCM => Register\Clock::PCM_DIV,
        self::PWM => Register\Clock::PWM_DIV
    ];

    static $CLOCK_FREQUENCIES = [
        Register\Clock::SRC_OSC => 192e5, //19.2MHz
        Register\Clock::SRC_PLLA => 0,
        Register\Clock::SRC_PLLC => 100e7, //1GHz
        Register\Clock::SRC_PLLD => 500e6, //500MHz
        Register\Clock::SRC_HDMI => 216e6, //216MHz
    ];

    public function __construct(AbstractBoard $board, $clock_number) {
        $this->board = $board;
        $this->clock_register = $this->board->getClockRegister();

        $this->div = static::$DIV[$clock_number];
        $this->ctl = static::$CTL[$clock_number];
    }

    /**
     * @param $frequency
     * @param int $src
     * @return $this
     * @throws InvalidValueException
     */
    public function start($frequency, $src = Register\Clock::SRC_OSC) {

        if(!isset(static::$CLOCK_FREQUENCIES[$src])){
            throw new InvalidValueException(sprintf('Invalid clock source'));
        }

        $base_frequency = static::$CLOCK_FREQUENCIES[$src];

        if($frequency < self::MIN_FREQUENCY || $frequency > $base_frequency){
            throw new InvalidValueException(sprintf('Frequency must be between %s and %s', self::MIN_FREQUENCY, $base_frequency));
        }


        $divi = $base_frequency / $frequency;
        $divr = $base_frequency % $frequency;
        $divf = ($divr * (1 << 12) / $base_frequency);

        $divi = min($divi, 4095);

        $this->clock_register[$this->div] = Register\AbstractRegister::BCM_PASSWORD | ($divi << 12) | $divf;
        usleep(10);

        $this->clock_register[$this->ctl] = Register\AbstractRegister::BCM_PASSWORD | $src;
        usleep(10);

        $this->clock_register[$this->ctl] |= Register\Clock::ENAB;

        return $this;
    }

    /**
     * @return $this
     */
    public function stop() {

        $this->clock_register[$this->ctl] = Register\AbstractRegister::BCM_PASSWORD | Register\Clock::KILL;
        usleep(110);

        //Wait for not busy
        while(($this->clock_register[$this->ctl] & Register\Clock::BUSY) != 0) {
            usleep(10);
        }

        return $this;
    }

}
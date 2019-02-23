<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board;
use Calcinai\PHPi\Exception\InvalidValueException;

class Clock extends AbstractPeripheral
{

    private $clock_register;

    private $div;
    private $ctl;

    private $source;

    const MIN_FREQUENCY = 0.0001;

    const GP0 = 0;
    const GP1 = 1;
    const GP2 = 2;
    const PCM = 3;
    const PWM = 4;

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

    /**
     * Clock constructor.
     *
     * @param Board $board
     * @param $clock_number
     * @param int $src
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     * @throws \ReflectionException
     * @throws InvalidValueException
     */
    public function __construct(Board $board, $clock_number, $src = Register\Clock::SRC_OSC)
    {
        $this->board = $board;
        $this->clock_register = $this->board->getClockRegister();

        $this->div = static::$DIV[$clock_number];
        $this->ctl = static::$CTL[$clock_number];

        $this->setSource($src);
    }

    /**
     * @param $src
     * @throws InvalidValueException
     */
    public function setSource($src)
    {
        if (!isset(static::$CLOCK_FREQUENCIES[$src])) {
            throw new InvalidValueException(sprintf('Invalid clock source'));
        }

        $this->source = $src;
        $this->clock_register[$this->ctl] |= Register\AbstractRegister::BCM_PASSWORD | $src;
        usleep(10);
    }

    /**
     * @param $frequency
     * @throws InvalidValueException
     */
    public function setFrequency($frequency)
    {
        $was_running = $this->isRunning();

        if ($was_running) {
            $this->stop();
        }

        $base_frequency = static::$CLOCK_FREQUENCIES[$this->source];

        if ($frequency < self::MIN_FREQUENCY || $frequency > $base_frequency) {
            throw new InvalidValueException(sprintf('Frequency must be between %s and %s', self::MIN_FREQUENCY, $base_frequency));
        }

        $divi = $base_frequency / $frequency;
        $divr = $base_frequency % $frequency;
        $divf = ($divr * (1 << 12) / $base_frequency);

        $divi = min($divi, 4095);

        $this->clock_register[$this->div] = Register\AbstractRegister::BCM_PASSWORD | ($divi << 12) | $divf;
        usleep(10);

        if ($was_running) {
            $this->start();
        }
    }

    /**
     * @return $this
     */
    public function start()
    {
        $this->clock_register[$this->ctl] = Register\AbstractRegister::BCM_PASSWORD | Register\Clock::ENAB;

        return $this;
    }

    /**
     * @return $this
     */
    public function stop()
    {
        $this->clock_register[$this->ctl] = Register\AbstractRegister::BCM_PASSWORD | 0x01;
        usleep(110);

        //Wait for not busy
        while (($this->clock_register[$this->ctl] & Register\Clock::BUSY) != 0) {
            usleep(1);
        }

        return $this;
    }

    public function isRunning()
    {
        return !!$this->clock_register[$this->ctl] & Register\Clock::ENAB;
    }

}

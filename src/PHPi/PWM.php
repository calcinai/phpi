<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;


use Calcinai\PHPi\Board\AbstractBoard;
use Calcinai\PHPi\Exception\InvalidValueException;

class PWM {

    const DEFAULT_DUTY_CYCLE  = 50;
    const DEFAULT_FREQUENCY   = 1000;
    const DEFAULT_RANGE       = 1024;

    const BASE_CLOCK_FREQENCY = 19200000;
    const MIN_FREQUENCY       = 1e-5;

    /**
     * @var AbstractBoard
     */
    private $board;

    /**
     * The number of the BCM pwm (0|1)
     *
     * @var int
     */
    private $pwm_number;

    /**
     * PWM constructor.
     * @param AbstractBoard $board
     * @param $pwm_number
     */


    /**
     * Duty cycle
     *
     * @var int
     */
    private $duty_cycle;

    /**
     * @var int
     */
    private $frequency;

    /**
     * The PWM range register.
     *
     * @var int
     */
    private $range;

    public function __construct(AbstractBoard $board, $pwm_number) {
        $this->board = $board;
        $this->pwm_number = $pwm_number;

        $this->is_active = false;

        $this->setDutyCycle(self::DEFAULT_DUTY_CYCLE);
        $this->setFrequency(self::DEFAULT_FREQUENCY);
        $this->setRange(self::DEFAULT_RANGE);
    }


    /**
     * Duty cucle as a percentage
     *
     * @param $duty
     * @return $this
     * @throws InvalidValueException
     */
    public function setDutyCycle($duty){

        if($duty < 0 || $duty > 100){
            throw new InvalidValueException('Duty cycle must be between 0 and 100%');
        }

        $this->duty_cycle = $duty;
        $this->board->getPWMRegister()->offsetSet(Register\PWM::$DAT[$this->pwm_number], $this->duty_cycle / 100 * $this->range);
        usleep(10);

        return $this;
    }

    /**
     * @param $frequency
     * @return $this
     * @throws InvalidValueException
     */
    public function setFrequency($frequency){

        if($frequency < self::MIN_FREQUENCY || $frequency > self::BASE_CLOCK_FREQENCY){
            throw new InvalidValueException(sprintf('Frequency must be between %s and %s', self::MIN_FREQUENCY, self::BASE_CLOCK_FREQENCY));
        }

        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @param $range
     * @return $this
     */
    public function setRange($range){

        $this->range = $range;
        $this->board->getPWMRegister()->offsetSet(Register\PWM::$RNG[$this->pwm_number], $this->range);
        usleep(10);

        return $this;
    }

    /**
     * Start the PWM.
     *
     * The wiringPi & Hussam al-Hertani implementations made this a lot quicker to implement.
     *
     * @return $this
     */
    public function start(){

        $clock_reg = $this->board->getClockRegister();

        $clock_reg[Register\Clock::CNTL] = Register\AbstractRegister::BCM_PASSWORD | Register\Clock::KILL;
        usleep(110);

        //Wait for not busy
        while(($clock_reg[Register\Clock::CNTL] & Register\Clock::BUSY) != 0) {
            usleep(100);
        }

        $clock_reg[Register\Clock::DIV] = Register\AbstractRegister::BCM_PASSWORD | ($this->getDivisor() << 12);

        $clock_reg[Register\Clock::CNTL] =
            Register\AbstractRegister::BCM_PASSWORD |
            Register\Clock::ENAB |
            Register\Clock::SRC_OSC;

        $pwm_reg = $this->board->getPWMRegister();
        $pwm_reg[Register\PWM::CTL] |= Register\PWM::$PWEN[$this->pwm_number];

        return $this;
    }

    /**
     * Stops the PWM (doesn't actually stop the clock, just disables)
     *
     * @return $this
     */
    public function stop(){

        $pwm_reg = $this->board->getPWMRegister();
        $pwm_reg[Register\PWM::CTL] &= ~Register\PWM::$PWEN[$this->pwm_number];

        // needs some time until the PWM module gets disabled, apparently without the delay the PWM module crashs
        usleep(1);

        return $this;
    }

    public function restart(){
        return $this->stop()->start();
    }

    /**
     * Calculate divisor value for PWM1 clock. Base frequency is 19.2MHz
     *
     * Not sure if this works correctly
     *
     * @return float
     */
    private function getDivisor(){

        $divi = self::BASE_CLOCK_FREQENCY / $this->frequency;
        $divr = self::BASE_CLOCK_FREQENCY % $this->frequency;
        $divf = $divr * $this->range / self::BASE_CLOCK_FREQENCY;

        if ($divi > $this->range -1){
            $divi = $this->range -1;
        }

        return ($divi << 12) | $divf;
    }

    public function resetPWM(){

    }

}
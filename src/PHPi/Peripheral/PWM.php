<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board;
use Calcinai\PHPi\Exception\InvalidValueException;

class PWM extends AbstractPeripheral
{

    const DEFAULT_DUTY_CYCLE = 50;
    const DEFAULT_FREQUENCY  = 1000;
    const DEFAULT_RANGE      = 1024;

    const PWM0 = 0;
    const PWM1 = 1;


    private $is_active;


    private $pwm_register;

    /**
     * The number of the BCM pwm (0|1)
     *
     * @var int
     */
    private $pwm_number;


    /**
     * PWM constructor.
     *
     * @param Board $board
     * @param $pwm_number
     * @throws InvalidValueException
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function __construct(Board $board, $pwm_number)
    {

        $this->board = $board;
        $this->pwm_register = $board->getPWMRegister();
        $this->pwm_number = $pwm_number;

        $this->is_active = false;

        $this->setFrequency(self::DEFAULT_FREQUENCY);
        $this->setDutyCycle(self::DEFAULT_DUTY_CYCLE);
        $this->setRange(self::DEFAULT_RANGE);
        $this->setEnableMS(false);
    }

    /**
     * Duty cycle as a percentage
     *
     * @param $duty
     * @return $this
     * @throws InvalidValueException
     */
    public function setDutyCycle($duty)
    {
        if ($duty < 0 || $duty > 100) {
            throw new InvalidValueException('Duty cycle must be between 0 and 100%');
        }

        $range = $this->pwm_register[Register\PWM::$RNG[$this->pwm_number]];
        $this->pwm_register[Register\PWM::$DAT[$this->pwm_number]] = $duty / 100 * $range;
        usleep(10);

        return $this;
    }

    /**
     * @param $range
     * @return $this
     */
    public function setRange($range)
    {
        $this->pwm_register[Register\PWM::$RNG[$this->pwm_number]] = $range;
        usleep(10);

        return $this;
    }


    /**
     * @param $enable_ms
     * @return $this
     */
    public function setEnableMS($enable_ms)
    {
        if ($enable_ms) {
            $this->pwm_register[Register\PWM::CTL] |= Register\PWM::$MSEN[$this->pwm_number];
        } else {
            $this->pwm_register[Register\PWM::CTL] &= ~Register\PWM::$MSEN[$this->pwm_number];
        }

        usleep(10);

        return $this;
    }


    /**
     * Really just a proxy for changing the clock freq
     *
     * @param $frequency
     * @return $this
     * @throws InvalidValueException
     */
    public function setFrequency($frequency)
    {
        $current_control_reg = $this->pwm_register[Register\PWM::CTL];
        // Make sure both disabled
        $this->pwm_register[Register\PWM::CTL] = $current_control_reg & ~(Register\PWM::PWEN1 | Register\PWM::PWEN2);

        //Change clock frequency
        $this->board->getClock(Clock::PWM)->setFrequency($frequency);

        //Restore them to where they were
        $this->pwm_register[Register\PWM::CTL] = $current_control_reg;

        return $this;
    }


    /**
     * Start the PWM.
     *
     * The wiringPi & Hussam al-Hertani implementations made this a lot quicker to implement.
     *
     * @return $this
     */
    public function start()
    {
        $this->board->getClock(Clock::PWM)->start();

        $this->pwm_register[Register\PWM::CTL] |= Register\PWM::$PWEN[$this->pwm_number];

        return $this;
    }

    /**
     * Stops the PWM (doesn't actually stop the clock, just disables)
     *
     * @return $this
     */
    public function stop()
    {
        $this->pwm_register[Register\PWM::CTL] &= ~Register\PWM::$PWEN[$this->pwm_number];

        return $this;
    }


    /**
     * @return PWM
     * @throws InvalidValueException
     */
    public function restart()
    {
        return $this->stop()->start();
    }


}

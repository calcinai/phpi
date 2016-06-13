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
        $this->frequency = self::DEFAULT_FREQUENCY;

        $this->setDutyCycle(self::DEFAULT_DUTY_CYCLE);
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
     * Really just a proxy for changing the clock freq
     *
     * @param $frequency
     * @return $this
     * @throws InvalidValueException
     */
    public function setFrequency($frequency){

        $this->frequency = $frequency;
        $this->restart();

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

        $pwm_reg = $this->board->getPWMRegister();

        //Backup and stop all
        $pwm_ctl_state = $pwm_reg[Register\PWM::CTL];
        $pwm_reg[Register\PWM::CTL] = 0;

        $this->board->getClock(Clock::PWM)->stop()->start($this->frequency);

        //Restore settings
        $pwm_reg[Register\PWM::CTL] = Register\PWM::$PWEN[$this->pwm_number];
//        $pwm_reg[Register\PWM::CTL] = $pwm_ctl_state | Register\PWM::$PWEN[$this->pwm_number];

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


}
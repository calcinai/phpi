<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board;

/**
 * Credit to bcm2835.c for the constants and impl hints
 *
 * Currently only supporting SPI0, the other two are conditionally in the auxiliary reg.
 *
 * Class SPI
 * @package Calcinai\PHPi\Peripheral
 */
class SPI extends AbstractPeripheral {

    const SPI0 = 0;

    const CS0 = 0; //Chip Select 0
    const CS1 = 1; //Chip Select 1
    const CS2 = 2; //Chip Select 2 (ie pins CS1 and CS2 are asserted)
    const CS_NONE = 3; // CS, control it yourself


    const SYSTEM_CLOCK_SPEED = 250e6; //250MHz - Haven't had time to check this yet.

    private $spi_number;

    /**
     * @var Register\SPI
     */
    private $spi_register;


    public function __construct(Board $board, $spi_number) {

        $this->board = $board;
        $this->spi_number = $spi_number;
        $this->spi_register = $board->getSPIRegister();

    }

    /**
     * @param $frequency
     * @return $this
     */
    public function setClockSpeed($frequency){
        $divisor =  self::SYSTEM_CLOCK_SPEED / $frequency;

        $this->spi_register[Register\SPI::CLK] = round($divisor);

        return $this;
    }


    /**
     * Since this is a register state, there's no need to call it on each transfer
     * (although you could if you were addressing multiple chis)
     *
     * @param $cex
     * @return $this
     */
    public function chipSelect($cex) {
        $this->spi_register[Register\SPI::CS] = Register\SPI::CS_CS & $cex;

        return $this;
    }


    /**
     *
     * TODO - handle interrupts and mem barriers
     *
     * Maxes out at about 3kB/s, sorry!
     * Can get to about 20kB/s with ext-mmap - woohoo!
     *
     * @param $tx_buffer
     * @param int $cex
     * @return mixed
     */
    public function transfer($tx_buffer, $cex = null){

        if($cex !== null){
            $this->chipSelect($cex);
        }

        $this->startTransfer();

        //Unpack the bytes and send/receive one by one
        //Slow because of the shallow FIFO
        //Also need to pack and unpack so there's a sensible interface to send data
        $rx_buffer = '';
        foreach(unpack('C*', $tx_buffer) as $char){

            $rx_buffer .= $this->transferByte($char);
        }

        //This one might not be nesessary
        while (!($this->spi_register[Register\SPI::CS] & Register\SPI::CS_DONE)) {
            usleep(1);
        }

        $this->endTransfer();
        
        return $rx_buffer;
    }




    private function transferByte($byte){

        // Wait for cts
        while(!($this->spi_register[Register\SPI::CS] & Register\SPI::CS_TXD)){
            usleep(1);
        }
        $this->spi_register[Register\SPI::FIFO] = $byte; //Just in case (PHP)

        //Wait for FIFO to be populated
        while(!($this->spi_register[Register\SPI::CS] & Register\SPI::CS_RXD)){
            usleep(1);
        }

        return pack('C', $this->spi_register[Register\SPI::FIFO]);

    }


    private function startTransfer(){
        //Clear TX and RX FIFO, set TA
        $this->spi_register[Register\SPI::CS] |= Register\SPI::CS_CLEAR | Register\SPI::CS_TA;
    }

    private function endTransfer(){
        //Clear TA
        $this->spi_register[Register\SPI::CS] &= ~Register\SPI::CS_TA;
    }

}
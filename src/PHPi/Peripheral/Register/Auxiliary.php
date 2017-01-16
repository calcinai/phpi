<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

/**
 * For some stupid reason, CIFS fails if this class is called 'Aux' so has to be this so I can dev.
 *
 * Class Auxiliary
 * @package Calcinai\PHPi\Peripheral\Register
 */
class Auxiliary extends AbstractRegister
{

    const AUX_IRQ     = 0x000; //Auxiliary Interrupt status
    const AUX_ENABLES = 0x004; //Auxiliary enables

    const AUX_MU_IO_REG   = 0x040; //Mini UART I/O Data
    const AUX_MU_IER_REG  = 0x044; //Mini UART Interrupt Enable
    const AUX_MU_IIR_REG  = 0x048; //Mini UART Interrupt Identify
    const AUX_MU_LCR_REG  = 0x04C; //Mini UART Line Control
    const AUX_MU_MCR_REG  = 0x050; //Mini UART Modem Control
    const AUX_MU_LSR_REG  = 0x054; //Mini UART Line Status
    const AUX_MU_MSR_REG  = 0x058; //Mini UART Modem Status
    const AUX_MU_SCRATCH  = 0x05C; //Mini UART Scratch
    const AUX_MU_CNTL_REG = 0x060; //Mini UART Extra Control
    const AUX_MU_STAT_REG = 0x064; //Mini UART Extra Status
    const AUX_MU_BAUD_REG = 0x068; //Mini UART Baudrate

    const AUX_SPI0_CNTL0_REG = 0x080; //SPI 1 Control register 0
    const AUX_SPI0_CNTL1_REG = 0x084; //SPI 1 Control register 1
    const AUX_SPI0_STAT_REG  = 0x088; //SPI 1 Status
    const AUX_SPI0_IO_REG    = 0x090; //SPI 1 Data
    const AUX_SPI0_PEEK_REG  = 0x094; //SPI 1 Peek

    const AUX_SPI1_CNTL0_REG = 0x0C0; //SPI 2 Control register 0
    const AUX_SPI1_CNTL1_REG = 0x0C4; //SPI 2 Control register 1
    const AUX_SPI1_STAT_REG  = 0x0C8; //SPI 2 Status
    const AUX_SPI1_IO_REG    = 0x0D0; //SPI 2 Data
    const AUX_SPI1_PEEK_REG  = 0x0D4; //SPI 2 Peek


    //Bits in the registers
    const AUXIRQ_UART = 0b0001;
    const AUXIRQ_SPI0 = 0b0010;
    const AUXIRQ_SPI1 = 0b0100;

    const AUXENB_UART = 0b0001;
    const AUXENB_SPI0 = 0b0010;
    const AUXENB_SPI1 = 0b0100;


    public static function getOffset()
    {
        return 0x215000;
    }

}
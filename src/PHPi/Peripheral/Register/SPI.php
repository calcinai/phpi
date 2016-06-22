<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;


class SPI extends AbstractRegister implements RegisterInterface {

    const SPI_CS   = 0;
    const SPI_FIFO = 1;
    const SPI_CLK  = 2;
    const SPI_DLEN = 3;
    const SPI_LTOH = 4;
    const SPI_DC   = 5;

    const SPI_CS_LEN_LONG    = (1<<25);
    const SPI_CS_DMA_LEN     = (1<<24);
    const SPI_CS_RXF         = (1<<20);
    const SPI_CS_RXR         = (1<<19);
    const SPI_CS_TXD         = (1<<18);
    const SPI_CS_RXD         = (1<<17);
    const SPI_CS_DONE        = (1<<16);
    const SPI_CS_LEN         = (1<<13);
    const SPI_CS_REN         = (1<<12);
    const SPI_CS_ADCS        = (1<<11);
    const SPI_CS_INTR        = (1<<10);
    const SPI_CS_INTD        = (1<<9);
    const SPI_CS_DMAEN       = (1<<8);
    const SPI_CS_TA          = (1<<7);

    const SPI_MODE0 = 0;
    const SPI_MODE1 = 1;
    const SPI_MODE2 = 2;
    const SPI_MODE3 = 3;

    const SPI_CS0     = 0;
    const SPI_CS1     = 1;
    const SPI_CS2     = 2;


    public static function getOffset() {
        return 0x00204000;
    }
}
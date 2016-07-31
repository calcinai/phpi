<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;


class SPI extends AbstractRegister implements RegisterInterface {

    const CS    = 0x0000;  //SPI Master Control and Status
    const FIFO  = 0x0004;  //SPI Master TX and RX FIFOs
    const CLK   = 0x0008;  //SPI Master Clock Divider
    const DLEN  = 0x000c;  //SPI Master Data Length
    const LTOH  = 0x0010;  //SPI LOSSI mode TOH
    const DC    = 0x0014;  //SPI DMA DREQ Controls

    const CS_LEN_LONG = 0x02000000;  //Enable Long data word in Lossi mode if DMA_LEN is set
    const CS_DMA_LEN  = 0x01000000;  //Enable DMA mode in Lossi mode
    const CS_CSPOL2   = 0x00800000;  //Chip Select 2 Polarity
    const CS_CSPOL1   = 0x00400000;  //Chip Select 1 Polarity
    const CS_CSPOL0   = 0x00200000;  //Chip Select 0 Polarity
    const CS_RXF      = 0x00100000;  //RXF - RX FIFO Full
    const CS_RXR      = 0x00080000;  //RXR RX FIFO needs Reading (full)
    const CS_TXD      = 0x00040000;  //TXD TX FIFO can accept Data
    const CS_RXD      = 0x00020000;  //RXD RX FIFO contains Data
    const CS_DONE     = 0x00010000;  //Done transfer Done
    const CS_TE_EN    = 0x00008000;  //Unused
    const CS_LMONO    = 0x00004000;  //Unused
    const CS_LEN      = 0x00002000;  //LEN LoSSI enable
    const CS_REN      = 0x00001000;  //REN Read Enable
    const CS_ADCS     = 0x00000800;  //ADCS Automatically Deassert Chip Select
    const CS_INTR     = 0x00000400;  //INTR Interrupt on RXR
    const CS_INTD     = 0x00000200;  //INTD Interrupt on Done
    const CS_DMAEN    = 0x00000100;  //DMAEN DMA Enable
    const CS_TA       = 0x00000080;  //Transfer Active
    const CS_CSPOL    = 0x00000040;  //Chip Select Polarity
    const CS_CLEAR    = 0x00000030;  //Clear FIFO Clear RX and TX
    const CS_CLEAR_RX = 0x00000020;  //Clear FIFO Clear RX
    const CS_CLEAR_TX = 0x00000010;  //Clear FIFO Clear TX
    const CS_CPOL     = 0x00000008;  //Clock Polarity
    const CS_CPHA     = 0x00000004;  //Clock Phase
    const CS_CS       = 0x00000003;  //Chip Select


    public static function getOffset() {
        return 0x204000;
    }
}
<?php

/**
 * @package    michael
 * @author     Michael Calcinai <michael@calcin.ai>
 */
class PinFunction {

    /**
     * BSC master 0 data line
     **/
    const SDA0 = 'SDA0';

    /**
     * BSC master 0 clock line
     **/
    const SCL0 = 'SCL0';

    /**
     * BSC master 1 data line
     **/
    const SDA1 = 'SDA1';

    /**
     * BSC master 1 clock line
     **/
    const SCL1 = 'SCL1';

    /**
     * General purpose Clock 0
     **/
    const GPCLK0 = 'GPCLK0';

    /**
     * General purpose Clock 1
     **/
    const GPCLK1 = 'GPCLK1';

    /**
     * General purpose Clock 2
     **/
    const GPCLK2 = 'GPCLK2';

    /**
     * SPI0 Chip select 1
     **/
    const SPI0_CE1_N = 'SPI0_CE1_N';

    /**
    * SPI0 Chip select 0
    **/
    const SPI0_CE0_N = 'SPI0_CE0_N';

    /**
    * SPI0 MISO
    **/
    const SPI0_MISO = 'SPI0_MISO';

    /**
    * SPI0 MOSI
    **/
    const SPI0_MOSI = 'SPI0_MOSI';

    /**
    * SPI0 Serial clock
    **/
    const SPI0_SCLK = 'SPI0_SCLK';

    /**
    * Pulse Width Modulator 0..1
    **/
    const PWMx = 'PWMx';

    /**
    * UART 0 Transmit Data
    **/
    const TXD0 = 'TXD0';

    /**
    * UART 0 Receive Data
    **/
    const RXD0 = 'RXD0';

    /**
    * UART 0 Clear To Send
    **/
    const CTS0 = 'CTS0';

    /**
    * UART 0 Request To Send
    **/
    const RTS0 = 'RTS0';

    /**
    * PCM clock
    **/
    const PCM_CLK = 'PCM_CLK';

    /**
    * PCM Frame Sync
    **/
    const PCM_FS = 'PCM_FS';

    /**
    * PCM Data in
    **/
    const PCM_DIN = 'PCM_DIN';

    /**
    * PCM data out
    **/
    const PCM_DOUT = 'PCM_DOUT';

    /**
    * Secondary mem Address bus
    **/
    const SAx = 'SAx';

    /**
    * Secondary mem. Controls
    **/
    const SOE_N  = 'SOE_N ';/ SE
    /**
    * Secondary mem. Controls
    **/
    const SWE_N  = 'SWE_N ';/ SRW_N
    /**
    * Secondary mem. data bus
    **/
    const SDx = 'SDx';

    /**
    * BSC slave Data, SPI slave MOSI
    **/
    const BSCSL  = 'BSCSL S;DA / MOSI
    /**
    * BSC slave Clock, SPI slave clock
    **/
    const BSCSL  = 'BSCSL S;CL / SCLK
    /**
    * BSC <not used>, SPI MISO
    **/
    const BSCSL  = 'BSCSL ';- / MISO
    /**
    * BSC <not used>, SPI CSn
    **/
    const BSCSL  = 'BSCSL ';- / CE_N
    /**
    * SPI1 Chip select 0-2
    **/
    const SPI1_CEx_N = 'SPI1_CEx_N';

    /**
    * SPI1 MISO
    **/
    const SPI1_MISO = 'SPI1_MISO';

    /**
    * SPI1 MOSI
    **/
    const SPI1_MOSI = 'SPI1_MOSI';

    /**
    * SPI1 Serial clock
    **/
    const SPI1_SCLK = 'SPI1_SCLK';
     1 Transmit Data
RXD1
UART 1 Receive Data
CTS    TXD1
    const   = 'UART 1; Clear To Send
RTS1
UART 1 Request To Send
SPI2_CEx_N
SPI2 Chip select 0-2
SPI2_MISO
SPI2 MISO
SPI2_MOSI
SPI2 MOSI
SPI2_SCLK
SPI2 Serial clock
ARM_TRST
ARM JTAG reset
ARM_RTCK
ARM JTAG return clock
ARM_TDO
ARM JTAG Data out
ARM_TCK
ARM JTAG Clock
ARM_TDI
ARM JTAG Data in
ARM_TMS
ARM JTAG Mode select
PCLK
DPI Pixel Clock
DE
DPI Data Enable
LCD_VSYNC
DPI Vertical Sync
LCD_HSYNC
DPI Horizontal Sync
DPI_Dx
DPI Parallel Data

}
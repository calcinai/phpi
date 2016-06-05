<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin;

class PinFunction {

    /**
     * Binary function selectors.  Each pin has 3 bits.  4 registers total.
     */
    const INPUT    = 0b000;
    const OUTPUT   = 0b001;
    const ALT0     = 0b100;
    const ALT1     = 0b101;
    const ALT2     = 0b110;
    const ALT3     = 0b111;
    const ALT4     = 0b011;
    const ALT5     = 0b010;


    /**
     * Named alternative functions - these are used in the board function matrices
     */
    
    /** BSC master 0 data line */
    const SDA0 = 'SDA0';

    /** BSC master 0 clock line */
    const SCL0 = 'SCL0';

    /** BSC master 1 data line */
    const SDA1 = 'SDA1';

    /** BSC master 1 clock line */
    const SCL1 = 'SCL1';

    /** General purpose Clock 0 */
    const GPCLK0 = 'GPCLK0';

    /** General purpose Clock 1 */
    const GPCLK1 = 'GPCLK1';

    /** General purpose Clock 2 */
    const GPCLK2 = 'GPCLK2';

    /** SPI0 Chip select 1 */
    const SPI0_CE1_N = 'SPI0_CE1_N';

    /** SPI0 Chip select 0 */
    const SPI0_CE0_N = 'SPI0_CE0_N';

    /** SPI0 MISO */
    const SPI0_MISO = 'SPI0_MISO';

    /** SPI0 MOSI */
    const SPI0_MOSI = 'SPI0_MOSI';

    /** SPI0 Serial clock */
    const SPI0_SCLK = 'SPI0_SCLK';

    /** Pulse Width Modulator 0 */
    const PWM0 = 'PWM0';

    /** Pulse Width Modulator 1 */
    const PWM1 = 'PWM1';

    /** UART 0 Transmit Data */
    const TXD0 = 'TXD0';

    /** UART 0 Receive Data */
    const RXD0 = 'RXD0';

    /** UART 0 Clear To Send */
    const CTS0 = 'CTS0';

    /** UART 0 Request To Send */
    const RTS0 = 'RTS0';

    /** PCM clock */
    const PCM_CLK = 'PCM_CLK';

    /** PCM Frame Sync */
    const PCM_FS = 'PCM_FS';

    /** PCM Data in */
    const PCM_DIN = 'PCM_DIN';

    /** PCM data out */
    const PCM_DOUT = 'PCM_DOUT';

    /** SPI1 Chip select 0 */
    const SPI1_CE0_N = 'SPI1_CE0_N';

    /** SPI1 Chip select 1 */
    const SPI1_CE1_N = 'SPI1_CE1_N';

    /** SPI1 Chip select 2 */
    const SPI1_CE2_N = 'SPI1_CE2_N';

    /** SPI1 MISO */
    const SPI1_MISO = 'SPI1_MISO';

    /** SPI1 MOSI */
    const SPI1_MOSI = 'SPI1_MOSI';

    /** SPI1 Serial clock */
    const SPI1_SCLK = 'SPI1_SCLK';

    /** UART 1 Transmit Data */
    const TXD1 = 'TXD1';

    /** UART 1 Receive Data */
    const RXD1 = 'RXD1';

    /** UART 1; Clear To Send */
    const CTS1 = 'CTS1';

    /** UART 1 Request To Send */
    const RTS1 = 'RTS1';

    /** SPI2 Chip select 0 */
    const SPI2_CE0_N = 'SPI2_CE0_N';

    /** SPI2 Chip select 1 */
    const SPI2_CE1_N = 'SPI2_CE1_N';

    /** SPI2 Chip select 2 */
    const SPI2_CE2_N = 'SPI2_CE2_N';

    /** SPI2 MISO */
    const SPI2_MISO = 'SPI2_MISO';

    /** SPI2 MOSI */
    const SPI2_MOSI = 'SPI2_MOSI';

    /** SPI2 Serial clock */
    const SPI2_SCLK = 'SPI2_SCLK';

    /** ARM JTAG reset */
    const ARM_TRST = 'ARM_TRST';

    /** ARM JTAG return clock */
    const ARM_RTCK = 'ARM_RTCK';

    /** ARM JTAG Data out */
    const ARM_TDO = 'ARM_TDO';

    /** ARM JTAG Clock */
    const ARM_TCK = 'ARM_TCK';

    /** ARM JTAG Data in */
    const ARM_TDI = 'ARM_TDI';

    /** ARM JTAG Mode select */
    const ARM_TMS = 'ARM_TMS';


}
<?php
/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Board\Feature\SoC;

use Calcinai\PHPRPi\Pin\PinFunction AS PF;

trait BCM2835 {
    public static function getPinFunctionMatrix(){
        return [
            0 =>  [PF::SDA0 => PF::ALT0],
            1 =>  [PF::SCL0 => PF::ALT0],
            2 =>  [PF::SDA1 => PF::ALT0],
            3 =>  [PF::SCL1 => PF::ALT0],
            4 =>  [PF::GPCLK0 => PF::ALT0, PF::ARM_TDI => PF::ALT5],
            5 =>  [PF::GPCLK1 => PF::ALT0, PF::ARM_TDO => PF::ALT5],
            6 =>  [PF::GPCLK2 => PF::ALT0, PF::ARM_RTCK => PF::ALT5],
            7 =>  [PF::SPI0_CE1_N => PF::ALT0],
            8 =>  [PF::SPI0_CE0_N => PF::ALT0],
            9 =>  [PF::SPI0_MISO => PF::ALT0],
            10 => [PF::SPI0_MOSI => PF::ALT0],
            11 => [PF::SPI0_SCLK => PF::ALT0],
            12 => [PF::PWM0 => PF::ALT0, PF::ARM_TMS => PF::ALT5],
            13 => [PF::PWM1 => PF::ALT0, PF::ARM_TCK => PF::ALT5],
            14 => [PF::TXD0 => PF::ALT0, PF::TXD1 => PF::ALT5],
            15 => [PF::RXD0 => PF::ALT0, PF::RXD1 => PF::ALT5],
            16 => [PF::CTS0 => PF::ALT3, PF::SPI1_CE2_N => PF::ALT4, PF::CTS1 => PF::ALT5],
            17 => [PF::RTS0 => PF::ALT3, PF::SPI1_CE1_N => PF::ALT4, PF::RTS1 => PF::ALT5],
            18 => [PF::PCM_CLK => PF::ALT0, PF::SPI1_CE0_N => PF::ALT4, PF::PWM0 => PF::ALT5],
            19 => [PF::PCM_FS => PF::ALT0, PF::SPI1_MISO => PF::ALT4, PF::PWM1 => PF::ALT5],
            20 => [PF::PCM_DIN => PF::ALT0, PF::SPI1_MOSI => PF::ALT4, PF::GPCLK0 => PF::ALT5],
            21 => [PF::PCM_DOUT => PF::ALT0, PF::SPI1_SCLK => PF::ALT4, PF::GPCLK1 => PF::ALT5],
            22 => [PF::ARM_TRST => PF::ALT4],
            23 => [PF::ARM_RTCK => PF::ALT4],
            24 => [PF::ARM_TDO => PF::ALT4],
            25 => [PF::ARM_TCK => PF::ALT4],
            26 => [PF::ARM_TDI => PF::ALT4],
            27 => [PF::ARM_TMS => PF::ALT4],
            28 => [PF::SDA0 => PF::ALT0, PF::PCM_CLK => PF::ALT2],
            29 => [PF::SCL0 => PF::ALT0, PF::PCM_FS => PF::ALT2],
            30 => [PF::PCM_DIN => PF::ALT2, PF::CTS0 => PF::ALT3, PF::CTS1 => PF::ALT5],
            31 => [PF::PCM_DOUT => PF::ALT2, PF::CTS0 => PF::ALT3, PF::CTS1 => PF::ALT5],
            32 => [PF::GPCLK0 => PF::ALT0, PF::TXD0 => PF::ALT3, PF::TXD1 => PF::ALT5],
            33 => [PF::RXD0 => PF::ALT3, PF::RXD1 => PF::ALT5],
            34 => [PF::GPCLK0 => PF::ALT0],
            35 => [PF::SPI0_CE1_N => PF::ALT0],
            36 => [PF::SPI0_CE0_N => PF::ALT0, PF::TXD0 => PF::ALT2],
            37 => [PF::SPI0_MISO => PF::ALT0, PF::RXD0 => PF::ALT2],
            38 => [PF::SPI0_MOSI => PF::ALT0, PF::RTS0 => PF::ALT2],
            39 => [PF::SPI0_SCLK => PF::ALT0, PF::CTS0 => PF::ALT2],
            40 => [PF::PWM0 => PF::ALT0, PF::SPI2_MISO => PF::ALT4, PF::TXD1 => PF::ALT5],
            41 => [PF::PWM1 => PF::ALT0, PF::SPI2_MOSI => PF::ALT4, PF::RXD1 => PF::ALT5],
            42 => [PF::GPCLK1 => PF::ALT0, PF::SPI2_SCLK => PF::ALT4, PF::RTS1 => PF::ALT5],
            43 => [PF::GPCLK2 => PF::ALT0, PF::SPI2_CE0_N => PF::ALT4, PF::CTS1 => PF::ALT5],
            44 => [PF::GPCLK1 => PF::ALT0, PF::SDA0 => PF::ALT1, PF::SDA1 => PF::ALT2, PF::SPI2_CE1_N => PF::ALT4],
            45 => [PF::PWM1 => PF::ALT0, PF::SCL0 => PF::ALT1, PF::SCL1 => PF::ALT2, PF::SPI2_CE2_N => PF::ALT4],
            46 => [],
            47 => [],
            48 => [],
            49 => [],
            50 => [],
            51 => [],
            52 => [],
            53 => []
        ];
    }

    public static function getPeripheralBaseAddress(){
        return 0x20000000;
    }
}
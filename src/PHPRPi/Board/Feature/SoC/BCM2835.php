<?php
/**
 * @package    calcinai/php-rpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPRPi\Board\Feature\SoC;

use Calcinai\PHPRPi\Pin\Mode;

trait BCM2835 {
    public static function getPinModeMatrix(){
        return [
            0 =>  [Mode::SDA0 => Mode::ALT0],
            1 =>  [Mode::SCL0 => Mode::ALT0],
            2 =>  [Mode::SDA1 => Mode::ALT0],
            3 =>  [Mode::SCL1 => Mode::ALT0],
            4 =>  [Mode::GPCLK0 => Mode::ALT0, Mode::ARM_TDI => Mode::ALT5],
            5 =>  [Mode::GPCLK1 => Mode::ALT0, Mode::ARM_TDO => Mode::ALT5],
            6 =>  [Mode::GPCLK2 => Mode::ALT0, Mode::ARM_RTCK => Mode::ALT5],
            7 =>  [Mode::SPI0_CE1_N => Mode::ALT0],
            8 =>  [Mode::SPI0_CE0_N => Mode::ALT0],
            9 =>  [Mode::SPI0_MISO => Mode::ALT0],
            10 => [Mode::SPI0_MOSI => Mode::ALT0],
            11 => [Mode::SPI0_SCLK => Mode::ALT0],
            12 => [Mode::PWM0 => Mode::ALT0, Mode::ARM_TMS => Mode::ALT5],
            13 => [Mode::PWM1 => Mode::ALT0, Mode::ARM_TCK => Mode::ALT5],
            14 => [Mode::TXD0 => Mode::ALT0, Mode::TXD1 => Mode::ALT5],
            15 => [Mode::RXD0 => Mode::ALT0, Mode::RXD1 => Mode::ALT5],
            16 => [Mode::CTS0 => Mode::ALT3, Mode::SPI1_CE2_N => Mode::ALT4, Mode::CTS1 => Mode::ALT5],
            17 => [Mode::RTS0 => Mode::ALT3, Mode::SPI1_CE1_N => Mode::ALT4, Mode::RTS1 => Mode::ALT5],
            18 => [Mode::PCM_CLK => Mode::ALT0, Mode::SPI1_CE0_N => Mode::ALT4, Mode::PWM0 => Mode::ALT5],
            19 => [Mode::PCM_FS => Mode::ALT0, Mode::SPI1_MISO => Mode::ALT4, Mode::PWM1 => Mode::ALT5],
            20 => [Mode::PCM_DIN => Mode::ALT0, Mode::SPI1_MOSI => Mode::ALT4, Mode::GPCLK0 => Mode::ALT5],
            21 => [Mode::PCM_DOUT => Mode::ALT0, Mode::SPI1_SCLK => Mode::ALT4, Mode::GPCLK1 => Mode::ALT5],
            22 => [Mode::ARM_TRST => Mode::ALT4],
            23 => [Mode::ARM_RTCK => Mode::ALT4],
            24 => [Mode::ARM_TDO => Mode::ALT4],
            25 => [Mode::ARM_TCK => Mode::ALT4],
            26 => [Mode::ARM_TDI => Mode::ALT4],
            27 => [Mode::ARM_TMS => Mode::ALT4],
            28 => [Mode::SDA0 => Mode::ALT0, Mode::PCM_CLK => Mode::ALT2],
            29 => [Mode::SCL0 => Mode::ALT0, Mode::PCM_FS => Mode::ALT2],
            30 => [Mode::PCM_DIN => Mode::ALT2, Mode::CTS0 => Mode::ALT3, Mode::CTS1 => Mode::ALT5],
            31 => [Mode::PCM_DOUT => Mode::ALT2, Mode::CTS0 => Mode::ALT3, Mode::CTS1 => Mode::ALT5],
            32 => [Mode::GPCLK0 => Mode::ALT0, Mode::TXD0 => Mode::ALT3, Mode::TXD1 => Mode::ALT5],
            33 => [Mode::RXD0 => Mode::ALT3, Mode::RXD1 => Mode::ALT5],
            34 => [Mode::GPCLK0 => Mode::ALT0],
            35 => [Mode::SPI0_CE1_N => Mode::ALT0],
            36 => [Mode::SPI0_CE0_N => Mode::ALT0, Mode::TXD0 => Mode::ALT2],
            37 => [Mode::SPI0_MISO => Mode::ALT0, Mode::RXD0 => Mode::ALT2],
            38 => [Mode::SPI0_MOSI => Mode::ALT0, Mode::RTS0 => Mode::ALT2],
            39 => [Mode::SPI0_SCLK => Mode::ALT0, Mode::CTS0 => Mode::ALT2],
            40 => [Mode::PWM0 => Mode::ALT0, Mode::SPI2_MISO => Mode::ALT4, Mode::TXD1 => Mode::ALT5],
            41 => [Mode::PWM1 => Mode::ALT0, Mode::SPI2_MOSI => Mode::ALT4, Mode::RXD1 => Mode::ALT5],
            42 => [Mode::GPCLK1 => Mode::ALT0, Mode::SPI2_SCLK => Mode::ALT4, Mode::RTS1 => Mode::ALT5],
            43 => [Mode::GPCLK2 => Mode::ALT0, Mode::SPI2_CE0_N => Mode::ALT4, Mode::CTS1 => Mode::ALT5],
            44 => [Mode::GPCLK1 => Mode::ALT0, Mode::SDA0 => Mode::ALT1, Mode::SDA1 => Mode::ALT2,Mode::SPI2_CE1_N => Mode::ALT4],
            45 => [Mode::PWM1 => Mode::ALT0, Mode::SCL0 => Mode::ALT1, Mode::SCL1 => Mode::ALT2, Mode::SPI2_CE2_N => Mode::ALT4],
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
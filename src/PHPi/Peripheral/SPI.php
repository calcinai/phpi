<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board;
use Calcinai\PHPi\Peripheral\Register\Auxiliary;

class SPI {

    /**
     * @var Board
     */
    private $board;

    private $spi_number;

    /**
     * @var Register\SPI
     */
    private $spi_register;

    /**
     * @var Register\SPI
     */
    private $aux_register;


    static $AUXENB = [
        self::SPI0 => Auxiliary::AUXENB_SPI0,
        self::SPI1 => Auxiliary::AUXENB_SPI1,
    ];


    const SPI0  = 0;
    const SPI1  = 1;

    public function __construct(Board $board, $spi_number) {

        $this->board = $board;
        $this->spi_number = $spi_number;
        $this->spi_register = $board->getSPIRegister();
        $this->aux_register = $board->getAuxRegister();


        $this->aux_register[Auxiliary::AUX_ENABLES] |= static::$AUXENB[$spi_number];



    }
}
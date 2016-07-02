<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\ADC\Microchip;

use Calcinai\PHPi\Exception\InvalidChannelException;
use Calcinai\PHPi\Peripheral\SPI;
use Evenement\EventEmitterTrait;

abstract class MCP300x {

    use EventEmitterTrait {
        EventEmitterTrait::on as traitOn;
    }

    const EVENT_CHANGE   = 'change';

    private $spi;

    /**
     * MCP300x constructor.
     * @param SPI $spi
     * @param $spi_channel
     */
    public function __construct(SPI $spi, $spi_channel) {
        $this->spi = $spi;

        $this->spi
            ->chipSelect($spi_channel)
            ->setClockSpeed(1000000); //1MHz
    }

    public function read($channel_number){
        //gte because it's 0-indexed
        if($channel_number >= $this->getNumChannels()){
            throw new InvalidChannelException(sprintf('Channel %s out of range for this ADC', $channel_number));
        }

        //Non-diff mode.  Needs to be added, not hardcoded.
        $channel_bits = 0b10000000 | ($channel_number << 4) ;

        //start bit, channel/diff, padding for value
        $read_pack = pack('C*', 1, $channel_bits, 0);
        $read = unpack('C*', $this->spi->transfer($read_pack));

        return (($read[2] << 8) | $read[3]) & 0x3FF;
    }

//Need to think about this more.
//    /**
//     * Hijack to start polling
//     *
//     * @param $event
//     */
//    public function on($event, callable $function){
//        $this->traitOn($event, $function);
//
//        $this->spi->getBoard()->getLoop()->addPeriodicTimer(self::POLL_FREQUENCY, [$this, 'checkValue']);
//    }


    abstract public function getNumChannels();
}
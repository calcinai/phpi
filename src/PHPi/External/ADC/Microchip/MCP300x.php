<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\ADC\Microchip;

use Calcinai\PHPi\Exception\InvalidChannelException;
use Calcinai\PHPi\External\ADC\Microchip\MCP300x\Channel;
use Calcinai\PHPi\Peripheral\SPI;

abstract class MCP300x
{

    const DEFAULT_CLOCK_SPEED = 1e6; //1MHz

    private $spi;
    private $spi_channel;


    /**
     * @var Channel[]
     */
    private $channels;

    /**
     * MCP300x constructor.
     * @param SPI $spi
     * @param $spi_channel
     */
    public function __construct(SPI $spi, $spi_channel)
    {

        $this->spi = $spi;
        $this->spi->setClockSpeed(self::DEFAULT_CLOCK_SPEED);

        $this->spi_channel = $spi_channel;

        for ($channel_number = 0; $channel_number < $this->getNumChannels(); $channel_number++) {
            $this->channels[$channel_number] = new Channel($this, $channel_number);
        }
    }

    /**
     * @return SPI
     */
    public function getSPI()
    {
        return $this->spi;
    }

    /**
     * @param $channel_number
     * @return Channel
     * @throws InvalidChannelException
     */
    public function getChannel($channel_number)
    {

        if (!isset($this->channels[$channel_number])) {
            throw new InvalidChannelException(sprintf('Channel %s out of range for this ADC', $channel_number));
        }

        return $this->channels[$channel_number];
    }

    /**
     * @param $buffer
     * @return string
     */
    public function transfer($buffer)
    {
        return $this->spi->transfer($buffer, $this->spi_channel);
    }


    abstract public function getNumChannels();
}
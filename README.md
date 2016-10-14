# PHPi

[![Build Status](https://travis-ci.org/calcinai/phpi.svg?branch=master)](https://travis-ci.org/calcinai/phpi)
[![Latest Stable Version](https://poser.pugx.org/calcinai/phpi/v/stable)](https://packagist.org/packages/calcinai/phpi)

Event driven bindings for the Raspberry Pi GPIO. Supports A, A+, B, Brev2, B+, 2B, 3B, Compute Module and Pi Zero.

This library interacts (almost) directly with the peripheral registers for maximum functionality and speed.  See note on `mmap/dma`

> As there is no ability to `mmap` in PHP, by default, this has been delegated to a python subprocess.  The python has been kept to an absolute minimum 
> (<25 lines) in terms of complexity to allow flexibility at PHP level.

> **This means that you MUST have python installed alongside PHP for it to function.** _…sortof_

> There is also a [native PHP extension](https://github.com/calcinai/php-ext-mmap) that is a drop-in replacement for the python subprocess 
> which greatly improves performance.  I'd strongly recommend using it, especially with less powerful Pis.


This library will function without any kernel drivers/sysfs etc enabled.


## Setup

Using composer:

```
composer require calcinai/phpi
```

Although it is possible to add this to your own autoloader, it's not recommended as you'll have no control of the dependencies.  If you haven't 
used composer before, I strongly recommend you check it out at https://getcomposer.org

## Usage

All of this code is designed to be run in cli mode, as root to permit the memory access. It is not recommended to try and run this in a synchronous 
nature (namely under apache/nginx) as this would introduce stability and security issues.  See below for more information about webservices.

You can test your install and get a visual display of the pin states by running ```./bin/phpi info``` from the install directory.

The board factory:

```php
$board = \Calcinai\PHPi\Factory::create();
```

Minimal example of reading and setting a pin

```php

$pin = $board->getPin(17) //BCM pin number
             ->setFunction(PinFunction::INPUT)
             ->setPull(Pin::PULL_UP);

//Will be === to Pin::LEVEL_HIGH or Pin::LEVEL_LOW
var_dump($pin->level());

$pin->setFunction(PinFunction::OUTPUT)
$pin->high();
$pin->low();
```

Higher level devices and events

```php
$button = new Button($board->getPin(17));
$led = new LED($board->getPin(18));

$button->on('press', [$led, 'on']);
$button->on('release', [$led, 'off']);

$board->getLoop()->run();
```

### GPIO

GPIO (input) is the default mode of the pin objects. Alternate functions can be accessed by using the ```->setFunction(PinFunction::x)``` method.  It is
 recommended to use the function names as opposed to `ALT0..5` unless you know exactly what you're doing, as quite a lot are reserved.
A few useful classes are also included for digital interaction.  With the default python-mmap, you can expect a raw transition speed of ~20kHz, with the
native extension, it's more like 80kHz on a Pi 3.


### PWM

Hardware PWM is supported by this library, and to an extent, so is soft PWM.  As this code runs in the react event loop, it's not practical to 
interact with the ports more than a few hundred times/sec.


### SPI

SPI is supported along with some device protocols (MPC300x etc).  With the default python-mmap, it is limited to about 3kB/s before there is no CPU left!  With
the native extension, you can reach speeds of over 30kB/s.


### The event loop

One of the original reasons for basing this project on the [react event loop](https://github.com/reactphp/event-loop) was for the other components 
that can be leveraged.  A good example is the websocket server; it will run seamlessly inline with this library to provide *real time, 
bidirectional, non-polling* interaction with the Pi from any modern browser.

See [phpi-websocket](https://github.com/calcinai/phpi-websocket) for more on this.

## External Devices

There are included helper classes for interfacing with common devices, with more added regulary.

* Generic Inputs/Outputs
* Buttons
* LEDs
* Relays
* ADC (MCP 3xxx series)
* H Bridge Motors
* 2 and 4 Phase stepper motors


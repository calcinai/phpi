# php-rpi

Event driven bindings for the Raspberry Pi GPIO

This library interacts (almost) directly with the peripheral registers for maximum functionality and speed.  See note on `mmap/dma`

> As there is no ability to `mmap` in PHP, this has been delegated to a python subprocess.  The python has been kept to an absolute minimum (<25 lines) in 
> terms of complexity to allow flexibility at PHP level.

> This subprocess is plugable, so it should be easy enough to replace it with a PHP extension down the track.


### GPIO

GPIO (input) is the default mode of the pin objects. Alternate functions can be accessed by using the ```setMode()``` method.  It is
 recommended to use the function names as opposed to `ALT0..5` unless you know exactly what you're doing, as quite a lot are reserved.
A few useful classes are also included for digital interaction.


### PWM

Hardware PWM is supported by this library, and to an extent, so is soft PWM.  As this code runs in the react event loop, it's not practical to 
interact with the ports more than a few hundred times/sec.
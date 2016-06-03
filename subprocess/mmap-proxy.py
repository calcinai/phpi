#!/usr/bin/env python -u

import os
import sys
import mmap
import struct

filename = sys.argv[1]
block_size = int(sys.argv[2], 0)
offset = int(sys.argv[3], 0)

fd = os.open(filename, os.O_RDWR | os.O_SYNC)
mem = mmap.mmap(fd, block_size, offset=offset)
os.close(fd)


while True:
    buffer = sys.stdin.read(6)
    command = buffer[0]
    address = struct.unpack('b', buffer[1])[0]

    mem.seek(address)
    
    if(command == 'r'):
        sys.stdout.write(mem.read(4))
    elif(command == 'w'):
        mem.write(buffer[2:])
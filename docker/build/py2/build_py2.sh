#!/bin/bash

if [ -n "$(ls -A /build)" ]; then
    rm /build/*
fi

python2.7 -O -m py_compile /solution/solution.py 2> /build/error.txt

RESP=$?

if [ $RESP -eq 0 ]
then
    echo "OK" > /build/status.txt
    cp /solution/solution.py /build/solution.py
    echo "python2.7 /build/solution.py" > /build/run
else
    echo "FAIL" > /build/status.txt
fi
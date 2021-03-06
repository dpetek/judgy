#!/bin/bash

if [ -n "$(ls -A /build)" ]; then
    rm /build/*
fi

SOURCE=$1

python2.7 -O -m py_compile /solution/$SOURCE 2> /build/error.txt

RESP=$?

if [ $RESP -eq 0 ]
then
    echo "OK" > /build/status.txt
    cp /solution/$SOURCE /build/solution.py
    echo "python2.7 /build/solution.py" > /build/run
else
    echo "FAIL" > /build/status.txt
fi
#!/bin/bash

if [ -n "$(ls -A /build)" ]; then
    rm /build/* 2>&1 >/dev/null
fi

SOURCE=$1

g++ -Wall -O2 -o /build/solution -lm /solution/$SOURCE 2> /build/error.txt
RESP=$?

if [ $RESP -eq 0 ]
then
    echo "OK" > /build/status.txt
    echo "/build/solution" > /build/run
else
    echo "FAIL" > /build/status.txt
fi

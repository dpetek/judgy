#!/bin/bash

if [ -n "$(ls -A /build)" ]; then
    rm /build/* 2>&1 >/dev/null
fi

SOURCE=$1

php -l /solution/$SOURCE &> /build/error.txt

RESP=$?
if [ $RESP -eq 0 ]
then
    echo "OK" > /build/status.txt
    cp /solution/$SOURCE /build/solution.php
    echo "php /build/solution.php" > /build/run
else
    echo "FAIL" > /build/status.txt
fi


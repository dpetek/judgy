#!/bin/bash

if [ -n "$(ls -A /build)" ]; then
    rm /build/* 2>&1 >/dev/null
fi

php -l /solution/solution.php &> /build/error.txt

RESP=$?
if [ $RESP -eq 0 ]
then
    echo "OK" > /build/status.txt
    cp /solution/solution.php /build/solution.php
    echo "php /build/solution.php" > /build/run
else
    echo "FAIL" > /build/status.txt
fi


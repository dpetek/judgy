#!/bin/bash

if [ -n "$(ls -A /out)" ]; then
    rm /out/*
fi

BUILD_STATUS=$(cat /build/status.txt | tr '\n' ' ')

echo $BUILD_STATUS

if [ $BUILD_STATUS = "FAIL" ]; then
    BUILD_ERROR=/build/error.txt
    if [ -e $BUILD_ERROR ]; then
        if [ -s $BUILD_ERROR ]; then
            echo "<pre>"
            cat $BUILD_ERROR
            echo "</pre>"
            exit 1
        fi
    fi
fi

CMD=$(cat /build/run | tr '\n' ' ')

NUM_TESTS=10
############ RUN
for (( i=1; i<=$NUM_TESTS; i++)); do
    FILE=/in/input$i.txt
    #ulimit -v 10000
    #ulimit -m 10000
    #ulimit -s 10000

    timeout 2 $CMD < $FILE > /out/output$i.txt 2> /out/output_error$i.txt
    RUN_RESP=$?

    if [ $RUN_RESP -eq 0 ]
    then
        echo "OK" > /out/output_status$i.txt
    else
        if [ $RUN_RESP -eq 124 ]
        then
            echo "TLE" > /out/output_status$i.txt
        else
            echo "RTE" > /out/output_status$i.txt
        fi
    fi
done
exit 0

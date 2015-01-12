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
            echo "<div class='build-error'>"
            cat $BUILD_ERROR
            echo "</div>"
            exit 1
        fi
    fi
fi

CMD=$(cat /build/run | tr '\n' ' ')

NUM_TESTS=10
############ RUN
for FILE in $(ls /in/input*.txt); do
    if [ -e $FILE ]; then
        #ulimit -v 10000
        #ulimit -m 10000
        #ulimit -s 10000

        OUTPUT_FILE=$(echo $FILE | sed 's/\/in\/input/\/out\/output/g')
        ERROR_FILE=$(echo $FILE | sed 's/\/in\/input/\/out\/output_error/g')
        STATUS_FILE=$(echo $FILE | sed 's/\/in\/input/\/out\/output_status/g')

        timeout 2 $CMD < $FILE > $OUTPUT_FILE 2> $ERROR_FILE
        RUN_RESP=$?

        if [ $RUN_RESP -eq 124 ]
        then
            echo "TLE" > $STATUS_FILE
        else
            echo "OK" > $STATUS_FILE
        fi
    fi
done
exit 0

#!/bin/bash
NUM_TESTS=10
############ RUN
for (( i=1; i<=$NUM_TESTS; i++));
do
    FILE=/out/output$i.txt
    if [ -e $FILE ]; then
        echo "<div class='test-case'>"
        CORRECT=/correct_out/output$i.txt
        FILE_ERROR=/out/output_error$i.txt
        STATUS=$(cat /out/output_status$i.txt | tr '\n' ' ')
        if [ $STATUS = "OK" ]
        then
            if diff --ignore-blank-lines --ignore-trailing-space $FILE $CORRECT 2>&1 >/dev/null ; then
                echo "<p class='line-ok'>Test #$i OK</p>"
            else
                echo "<p class='line-wa'>Test #$i WA</p>"
            fi
        elif [ $STATUS = "TLE" ]
        then
            echo "<p class='line-tle'>Test #$i TLE</p>"
        elif [ $STATUS = "RTE" ]
        then
            echo "<p class='line-rte'>Test #$i RTE</p>"
        else
            echo "<p class='line-unknown'>Test #$i Unknown error</p>"
        fi
        if [ -e $FILE_ERROR ]; then
            echo "<div class='case-error'>"
            cat $FILE_ERROR
            echo "</div>"
        fi
        echo "</div>"
    fi
done
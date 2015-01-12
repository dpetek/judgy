#!/bin/bash
NUM_TESTS=10
############ RUN
for FILE in $(ls /out/output*.txt);
do
    if [ -e $FILE ]; then
        CORRECT=$(echo $FILE | sed 's/\/out\/output/\/correct_out\/output/g')
        if [ -e $CORRECT ]; then
            FILE_ERROR=$(echo $FILE | sed 's/\/out\/output/\/out\/output_error/g')
            STATUS_FILE=$(echo $FILE | sed 's/\/out\/output/\/out\/output_status/g')

            echo "<div class='test-case'>"
            STATUS=$(cat $STATUS_FILE | tr '\n' ' ')
            if [ $STATUS = "OK" ]
            then
                if diff --ignore-blank-lines --ignore-trailing-space $FILE $CORRECT 2>&1 >/dev/null ; then
                    echo "<p class='line-ok'>Correct answer</p>"
                else
                    echo "<p class='line-wa'>Wrong answer</p>"
                fi
            elif [ $STATUS = "TLE" ]
            then
                echo "<p class='line-tle'>Time limit exceeded</p>"
            elif [ $STATUS = "RTE" ]
            then
                echo "<p class='line-rte'>Runtime error</p>"
            else
                echo "<p class='line-unknown'>Unknown error</p>"
            fi
            if [ -e $FILE_ERROR ]; then
                echo "<div class='case-error'>"
                cat $FILE_ERROR
                echo "</div>"
            fi
            echo "</div>"
        fi
    fi
done
#!/bin/bash

if [ -n "$(ls -A /build)" ]; then
    rm /build/* 2>&1 >/dev/null
fi

SOURCE=$1
CLASS=$(basename $SOURCE .java)
DIR=$(echo $SOURCE | sed "s/\/$CLASS\.java//g")

javac /solution/$SOURCE &> /build/error.txt

RESP=$?
if [ $RESP -eq 0 ]
then
    echo "OK" > /build/status.txt
    cp /solution/$DIR/$CLASS.java /build/$CLASS.java
    cp /solution/$DIR/$CLASS.class /build/$CLASS.class
    echo "java -cp /build $CLASS" > /build/run
else
    echo "FAIL" > /build/status.txt
fi
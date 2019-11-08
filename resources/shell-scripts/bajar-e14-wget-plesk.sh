#!/bin/bash
for ((i=$2;i<=$3;i++));
do
    echo "Process #" $i
	nohup /opt/plesk/php/5.6/bin/php bajar-e14-marzo-11.php $1 wget $i  &
	echo "Running !"
                #sleep 1
	echo ""
done
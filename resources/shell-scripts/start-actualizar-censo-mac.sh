#!/bin/bash
for ((i=1;i<=$1;i++));
do
    echo "Process #" $i
	nohup php actualizar-censo.php &
	echo "Done."
                sleep 1
	echo ""
done
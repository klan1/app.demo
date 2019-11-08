#!/bin/bash
for ((i=1;i<=$1;i++));
do
    echo "Process #" $i
	nohup /opt/plesk/php/5.5/bin/php actualizar-censo.php &
	echo "Done."
                sleep 1
	echo ""
done
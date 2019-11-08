#!/bin/bash
echo "CAM:"
ls -l /var/www/vhosts/klan1.net/sie.klan1.net/v1/resources/e14/2018/CAM/ | wc -l
du -h /var/www/vhosts/klan1.net/sie.klan1.net/v1/resources/e14/2018/CAM/
echo "SEN:"
ls -l /var/www/vhosts/klan1.net/sie.klan1.net/v1/resources/e14/2018/SEN/ | wc -l
du -hS /var/www/vhosts/klan1.net/sie.klan1.net/v1/resources/e14/2018/SEN/


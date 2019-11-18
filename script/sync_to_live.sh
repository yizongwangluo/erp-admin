#!/usr/bin/env bash
echo "sync to live start,please wait..."
live_ip="182.254.243.246"
rsync -aH --delete --progress --exclude-from="sync_exclude_for_test.list" /home/www/www.jiaoyitu.com/ rsync_jiaoyitu@${live_ip}:/home/www/test.jiaoyitu.com

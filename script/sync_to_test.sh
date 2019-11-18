#!/usr/bin/env bash
echo "sync to test start,please wait..."
live_ip="192.168.8.219"
rsync -aH --delete --progress --exclude-from="sync_exclude_for_test.list" /data/www/www.jiaoyitu.com/ root@${live_ip}:/home/www/test.jiaoyitu.com
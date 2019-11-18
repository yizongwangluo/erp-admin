# coding=utf-8
import sys
import zipfile
import shutil
import os

src_apk = '/home/www/www.jiaoyitu.com/Apk/'+sys.argv[1]
target_apk = '/home/www/www.jiaoyitu.com/Apk/'+sys.argv[2]
target_channel = sys.argv[3]
src_empty_file = '/home/www/www.jiaoyitu.com/Apk/empty.txt'

shutil.copy(src_apk,  target_apk)

zipped = zipfile.ZipFile(target_apk, 'a', zipfile.ZIP_DEFLATED)
empty_channel_file = "META-INF/{channel}".format(channel = target_channel)
zipped.write(src_empty_file, empty_channel_file)
zipped.close()

print os.path.abspath('..')
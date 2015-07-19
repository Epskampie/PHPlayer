#!/bin/bash

sudo apt-get install git
git clone -b flexible https://github.com/Epskampie/PHPlayer.git music
cd music
php composer.phar install

# Create upload dirs
mkdir web/uploads

# Fix permissions
chmod -R 777 app/logs/
chmod -R 777 app/cache/
chmod -R 777 web/uploads/

# Make shell files runnable
chmod +x *.sh

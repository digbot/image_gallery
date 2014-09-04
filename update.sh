#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )";

if [ ! -d $DIR/app/logs ];  then
  mkdir ./app/logs
  chmod 777 ./app/logs -R
  setfacl -R -m u:apache:rwX -m u:`whoami`:rwX app/cache app/logs
fi

if [ ! -d $DIR/app/cache ];  then
  mkdir ./app/cache
  chmod 777 ./app/cache -R
  setfacl -dR -m u:apache:rwx -m u:`whoami`:rwx app/cache app/logs
fi

if [ ! -d $DIR/app/spool ];  then
  mkdir ./app/spool
  chmod 777 ./app/spool -R
fi

if [ ! -d $DIR/app/var  ];  then
 mkdir ./app/var
 chmod 777 ./app/var -R
fi

if [ ! -d $DIR/app/var/sessions  ];  then
 mkdir  ./app/var/sessions
 chmod 777  ./app/var/sessions  -R
fi

if [ ! -d $DIR/web/media  ];  then
 mkdir  ./web/media
fi

if [ ! -d $DIR/web/media/cache ]; then
 mkdir  ./web/media/cache
 chmod 777  ./web/media/cache -R
fi


if [ ! -f $DIR/web/index.php  ];  then
 cd ./web;
 ln -s app.php  index.php
fi



cd $DIR
if [ -f ./composer.phar ]; then
 php -d apc.enable_cli=0 -d xdebug.profiler_enable=0  composer.phar selfupdate --ansi
else
 curl -sS https://getcomposer.org/installer | php
fi
php -d apc.enable_cli=0 -d memory_limit=512M  -d xdebug.profiler_enable=0   composer.phar update --ansi -vv

echo "> Done!"
date

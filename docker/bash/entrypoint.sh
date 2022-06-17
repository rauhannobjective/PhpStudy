#!/bin/bash

sleep 10s

su -c "composer install" -s /bin/sh nginx
su -c "vendor/bin/phinx migrate" -s /bin/sh nginx
#su -c "vendor/bin/phinx seed:run" -s /bin/sh nginx

php-fpm

exit 0

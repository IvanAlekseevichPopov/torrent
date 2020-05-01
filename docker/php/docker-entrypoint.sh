#!/bin/sh

touch /var/www/.bash_history
mkdir -p /var/www/.composer
chown -R www-data: /var/www/.composer /var/www/.bash_history

: ${WWW_DATA_UID:=`stat -c %u /var/www/html`}

# Change www-data's uid & guid to be the same as directory in host or the configured one
if [ "`id -u www-data`" != "$WWW_DATA_UID" ]; then
    usermod -u $WWW_DATA_UID www-data
fi

touch /var/www/.bash_history
chown www-data: /var/www/.bash_history

gosu www-data composer install --prefer-dist --no-progress --no-suggest --no-interaction &

php-fpm -R

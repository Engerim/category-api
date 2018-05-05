#!/usr/bin/env bash
set -xe

if [ $1 == 'local' ]; then
    echo "disable post-deploy-cmd for local environment"
    sed -ri 's/composer.json/composer.json.dist/g' /build-scripts/entrypoint.sh

    echo "override exec to allow bash as www-data with correct user id"
    sed -ri 's/exec .*/sh \/local-dev\/build-scripts\/docker\/local.sh "$@"/g' /build-scripts/entrypoint.sh

    echo "install xdebug and gosu"
    cat /app/build-scripts/docker/xdebug.ini >> /app/php.ini
    sed -ri 's/internal/fastcgi_param PHP_VALUE "xdebug.remote_enable = 1";\ninternal/g' /app/nginx.conf
    echo "deb http://ftp.debian.org/debian jessie-backports main" >> /etc/apt/sources.list.d/backports.list
    apt-get update
    apt-get -t jessie-backports install -y gosu
    apt-get install -y --no-install-recommends build-essential autoconf gcc make
    pecl install xdebug-2.6.0
    apt-get purge -y --auto-remove autoconf gcc build-essential
    rm -rf /var/lib/apt/lists/*
    mv /app/build-scripts/docker/xdebug /usr/local/bin/xdebug

    curl -o /usr/local/bin/infection -L https://github.com/infection/infection/releases/download/0.8.0/infection.phar
    curl -o /usr/local/bin/infection.pubkey -L https://github.com/infection/infection/releases/download/0.8.0/infection.phar.pubkey
    chmod +x /usr/local/bin/infection

fi
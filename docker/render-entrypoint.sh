#!/bin/sh
set -e

port="${PORT:-8080}"

if [ -n "$port" ]; then
    if [ -f /etc/apache2/ports.conf ]; then
        sed -ri "s/^Listen 80/Listen ${port}/" /etc/apache2/ports.conf
    fi
    if [ -f /etc/apache2/sites-available/000-default.conf ]; then
        sed -ri "s#<VirtualHost \*:[0-9]+>#<VirtualHost *:${port}>#" /etc/apache2/sites-available/000-default.conf
    fi
fi

exec docker-php-entrypoint "$@"

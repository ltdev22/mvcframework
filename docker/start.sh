#!/usr/bin/env bash

set -e

env=${APP_ENV:-production}
# echo "The environment is $env"

if [ "$env" != "local" ]; then
  # Remove xdebug in production
  rm -rf /usr/local/etc/php/conf.d/{docker-php-ext-xdebug.ini, xdebug.ini}
fi

exec apache2-foreground
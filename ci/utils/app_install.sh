#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

php -d memory_limit=-1 /usr/local/bin/composer install
rm -rf app/cache/d*
php app/console doctrine:schema:drop --full-database --force --env=dev
php app/console doctrine:schema:update --force --env=dev
php app/console cache:clear --env=dev
php app/console doctrine:fixtures:load -n --env=dev
php app/console assets:install --env=dev
php app/console bazinga:js-translation:dump --env=dev
php app/console cache:clear --env=dev

#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

mysqldump -h "mysql" -u "root" -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" | gzip > "$MYSQL_DUMP_FILENAME"

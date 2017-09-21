#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Set app directory path to variable
app_directory=`pwd`

chown -R www-data:www-data "$app_directory"

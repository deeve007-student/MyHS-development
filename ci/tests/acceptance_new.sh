#!/bin/bash

set -xe

# Get amount of new acceptance tests (between current commit and master branch) and run them
# If there are no new tests - skip

tests_count=`git diff "${CI_COMMIT_SHA:0:8}" origin/master --name-only | grep '^tests/acceptance/.*\.php$' | wc -l`

if [ "$tests_count" = "0" ]; then
  echo "No new acceptance tests found"
else
  git diff "${CI_COMMIT_SHA:0:8}" origin/master --name-only | grep '^tests/acceptance/.*\.php$' | xargs codecept run
fi

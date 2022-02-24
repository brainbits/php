#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "setting env var COMPOSER_INSTALL_DEV executes composer install" {
  skip
  run docker run --rm -e COMPOSER_INSTALL_DEV=1 $TAG true
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 1 ]
  [[ "$output" == "Composer could not find a composer.json file in /app"* ]]
}

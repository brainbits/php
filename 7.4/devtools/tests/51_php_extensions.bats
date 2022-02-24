#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "php-pdo_sqlite is installed" {
  run docker run --rm $TAG php --ri pdo_sqlite
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-xmlwriter is installed" {
  run docker run --rm $TAG php --ri xmlwriter
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-ast is installed" {
  run docker run --rm $TAG php --ri ast
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "pcov is installed" {
  run docker run --rm $TAG php --ri pcov
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "pcov is disabled" {
  run docker run --rm $TAG bash -c 'php -i | grep "PCOV support"'
  echo "status: $status"
  echo "output: $output"
  [ "$output" == "PCOV support => Disabled" ]
}

#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "symfony installed" {
  run docker run --rm $TAG which symfony
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "symfony is working" {
  run docker run --rm $TAG symfony version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

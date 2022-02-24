#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "jq is installed" {
  run docker run --rm $TAG jq --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "yq is installed" {
  run docker run --rm $TAG yq --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

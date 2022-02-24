#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "composer installed" {
  run docker run --rm $TAG which composer
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer is working" {
  run docker run --rm $TAG composer diagnose
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer is version 2.x" {
  run docker run --rm $TAG composer --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "Composer version 2."* ]]
}

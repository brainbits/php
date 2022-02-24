#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "timezone is CEST" {
  run docker run --rm $TAG bash -c 'date +%Z'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "CEST" || "$output" == "CET" ]]
}

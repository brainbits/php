#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "alpine version is 3.15" {
  run docker run --rm $TAG bash -c 'cat /etc/os-release'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "${lines[2]}" == "VERSION_ID=3.15."* ]]
}

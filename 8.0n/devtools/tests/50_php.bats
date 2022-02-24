#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "phpdbg is installed" {
  run docker run --rm $TAG phpdbg --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "phpdbg "* ]]
}

@test "phpdbg uses PHP 8.0" {
  run docker run --rm $TAG bash -c "phpdbg --version | head -n 2 | tail -n 1"
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "PHP 8.0."* ]]
}

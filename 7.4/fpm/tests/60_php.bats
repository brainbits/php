#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "default value for php ini setting zend.exception_ignore_args is On" {
  run docker run --rm $TAG bash -c 'php -i | grep "zend.exception_ignore_args =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "zend.exception_ignore_args => On => On"* ]]
}

#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "brainbits coding standard is available" {
  run docker run --rm $TAG bash -c 'phpcs -i | grep -o BrainbitsCodingStandard | head -n 1'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "BrainbitsCodingStandard" ]]
}

@test "php-xdebug script is available" {
  run docker run --rm $TAG bash -c 'php-xdebug --ri xdebug'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-pcov script is available" {
run docker run --rm $TAG bash -c 'php-pcov --ri pcov'
echo "status: $status"
echo "output: $output"
[ "$status" -eq 0 ]
}

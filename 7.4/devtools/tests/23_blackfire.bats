#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "default state for php-blackfire is inactive" {
  run docker run --rm $TAG php --ri blackfire
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 1 ]
}

@test "setting env var PHP_ACTIVATE_BLACKFIRE to 1 activates php-blackfire" {
  run docker run --rm -e PHP_ACTIVATE_BLACKFIRE=1 $TAG php --ri blackfire
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "default blackfire.agent_socket is tcp://blackfire:8307" {
  run docker run --rm -e PHP_ACTIVATE_BLACKFIRE=1 $TAG bash -c 'php -i | grep blackfire.agent_socket'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "blackfire.agent_socket => tcp://blackfire:8307 => tcp://blackfire:8307"* ]]
}

@test "setting env var PHP_BLACKFIRE_SOCKET to blackfirehost sets php ini setting blackfire.agent_socket" {
  run docker run --rm -e PHP_ACTIVATE_BLACKFIRE=1 -e PHP_BLACKFIRE_SOCKET=blackfirehost $TAG bash -c 'php -i | grep blackfire.agent_socket'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "blackfire.agent_socket => blackfirehost => blackfirehost"* ]]
}

@test "setting env var PHP_ACTIVATE_BLACKFIRE to 0 deactivates php-blackfire" {
  run docker run --rm -e PHP_ACTIVATE_BLACKFIRE=0 $TAG php --ri blackfire
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 1 ]
}

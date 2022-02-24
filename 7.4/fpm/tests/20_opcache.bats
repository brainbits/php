#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "setting env var PHP_OPCACHE_ENABLE to 1 sets php ini setting opcache.enable to On" {
  run docker run --rm -e PHP_OPCACHE_ENABLE=1 $TAG bash -c 'php -i | grep "opcache.enable =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.enable => On => On"* ]]
}

@test "setting env var PHP_OPCACHE_ENABLE to 0 sets php ini setting opcache.enable to Off" {
  run docker run --rm -e PHP_OPCACHE_ENABLE=0 $TAG bash -c 'php -i | grep "opcache.enable =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.enable => Off => Off"* ]]
}

@test "setting env var PHP_OPCACHE_ENABLE_CLI to 1 sets php ini setting opcache.enable_cli to On" {
  run docker run --rm -e PHP_OPCACHE_ENABLE_CLI=1 $TAG bash -c 'php -i | grep "opcache.enable_cli =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.enable_cli => On => On"* ]]
}

@test "setting env var PHP_OPCACHE_ENABLE_CLI to 0 sets php ini setting opcache.enable_cli to Off" {
  run docker run --rm -e PHP_OPCACHE_ENABLE_CLI=0 $TAG bash -c 'php -i | grep "opcache.enable_cli =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.enable_cli => Off => Off"* ]]
}

@test "setting env var PHP_OPCACHE_VALIDATE_TIMESTAMPS to 1 sets php ini setting opcache.validate_timestamps to On" {
  run docker run --rm -e PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 $TAG bash -c 'php -i | grep "opcache.validate_timestamps =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.validate_timestamps => On => On"* ]]
}

@test "setting env var PHP_OPCACHE_VALIDATE_TIMESTAMPS to 0 sets php ini setting opcache.validate_timestamps to Off" {
  run docker run --rm -e PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 $TAG bash -c 'php -i | grep "opcache.validate_timestamps =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.validate_timestamps => Off => Off"* ]]
}

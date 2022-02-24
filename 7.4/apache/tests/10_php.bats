#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "php version is 7.4" {
  run docker run --rm $TAG php --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "PHP 7.4."* ]]
}

@test "default value for php ini setting date.timezone is Europe/Berlin" {
  run docker run --rm $TAG bash -c 'php -i | grep "date.timezone =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "date.timezone => Europe/Berlin => Europe/Berlin"* ]]
}

@test "default value for php ini setting opcache.enable is On" {
  run docker run --rm $TAG bash -c 'php -i | grep "opcache.enable =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.enable => On => On"* ]]
}

@test "default value php ini setting opcache.enable_cli is On" {
  run docker run --rm $TAG bash -c 'php -i | grep "opcache.enable_cli =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.enable_cli => On => On"* ]]
}

@test "default value for php ini setting opcache.validate_timestamps is Off" {
  run docker run --rm $TAG bash -c 'php -i | grep "opcache.validate_timestamps =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "opcache.validate_timestamps => Off => Off"* ]]
}

#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "default value for php ini setting date.timezone is Europe/Berlin" {
  run docker run --rm $TAG bash -c 'php -i | grep "date.timezone =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "date.timezone => Europe/Berlin => Europe/Berlin"* ]]
}

@test "default value for php ini setting display_errors is Off" {
  run docker run --rm $TAG bash -c 'php -i | grep "display_errors =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "display_errors => Off => Off"* ]]
}

@test "default value for php ini setting display_startup_errors is Off" {
  run docker run --rm $TAG bash -c 'php -i | grep "display_startup_errors =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "display_startup_errors => Off => Off"* ]]
}

@test "default value for php ini setting log_errors is On" {
  run docker run --rm $TAG bash -c 'php -i | grep "log_errors =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "log_errors => On => On"* ]]
}

@test "default value for php ini setting enable_dl is Off" {
  run docker run --rm $TAG bash -c 'php -i | grep "enable_dl =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "enable_dl => Off => Off"* ]]
}

@test "default value for php ini setting short_open_tag is Off" {
  run docker run --rm $TAG bash -c 'php -i | grep "short_open_tag =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "short_open_tag => Off => Off"* ]]
}

@test "default value for php ini setting error_reporting is 22527" {
  run docker run --rm $TAG bash -c 'php -i | grep "error_reporting =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "error_reporting => 22527 => 22527"* ]]
}

@test "default value for php ini setting request_order is GP" {
  run docker run --rm $TAG bash -c 'php -i | grep "request_order =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "request_order => GP => GP"* ]]
}

@test "default value for php ini setting zend.assertions is -1" {
  run docker run --rm $TAG bash -c 'php -i | grep "zend.assertions =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "zend.assertions => -1 => -1"* ]]
}

@test "default value for php ini setting zend.exception_ignore_args is On" {
  run docker run --rm $TAG bash -c 'php -i | grep "zend.exception_ignore_args =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "zend.exception_ignore_args => On => On"* ]]
}

@test "default value for php ini setting zend.exception_string_param_max_len is 0" {
  run docker run --rm $TAG bash -c 'php -i | grep "zend.exception_string_param_max_len =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "zend.exception_string_param_max_len => 0 => 0"* ]]
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

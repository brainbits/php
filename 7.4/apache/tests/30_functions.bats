#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "system_set_timezone() sets timezone" {
  run docker run --rm $TAG bash -c 'source /usr/bin/functions/functions.sh && system_set_timezone UTC > /dev/null && cat /etc/timezone'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "UTC" ]]
}

@test "php_set_timezone() sets time zone" {
  run docker run --rm $TAG bash -c 'source /usr/bin/functions/functions.sh && php_set_timezone UTC && php -i | grep date.timezone'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "date.timezone => UTC => UTC" ]]
}

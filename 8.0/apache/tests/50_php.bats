#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "apache only php ini setting core.memory_limit is set to 128M" {
  run docker run --rm $TAG bash -c 'grep memory_limit /etc/apache2/conf.d/php?-module.conf'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "php_value memory_limit 128M"* ]]
}

@test "php ini setting core.post_max_size is set to 200M" {
  run docker run --rm $TAG bash -c 'php -i | grep "post_max_size =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "post_max_size => 200M => 200M"* ]]
}

@test "php ini setting core.upload_max_filesize is set to 200M" {
  run docker run --rm $TAG bash -c 'php -i | grep "upload_max_filesize =>"'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "upload_max_filesize => 200M => 200M"* ]]
}

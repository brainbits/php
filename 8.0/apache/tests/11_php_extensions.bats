#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "php-fileinfo is installed" {
  run docker run --rm $TAG php --ri fileinfo
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-iconv is installed" {
  run docker run --rm $TAG php --ri iconv
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-intl is installed" {
  run docker run --rm $TAG php --ri intl
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-json is installed" {
  run docker run --rm $TAG php --ri json
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-mbstring is installed" {
  run docker run --rm $TAG php --ri mbstring
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-opcache is installed" {
  run docker run --rm $TAG php --ri "Zend Opcache"
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-openssl is installed" {
  run docker run --rm $TAG php --ri openssl
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-phar is installed" {
  run docker run --rm $TAG php --ri phar
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-session is installed" {
  run docker run --rm $TAG php --ri session
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "php-xml is installed" {
  run docker run --rm $TAG php --ri xml
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

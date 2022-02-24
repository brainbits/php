#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "composer installed" {
  run docker run --rm $TAG which composer
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer is working" {
  run docker run --rm $TAG composer diagnose
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer is version 2.x" {
  run docker run --rm $TAG composer --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "Composer version 2."* ]]
}

@test "composer2 installed" {
  run docker run --rm $TAG which composer2
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer2 is working" {
  run docker run --rm $TAG composer2 diagnose
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer2 is version 2.x" {
  run docker run --rm $TAG composer2 --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "Composer version 2."* ]]
}

@test "composer1 installed" {
  run docker run --rm $TAG which composer1
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer1 is working" {
  run docker run --rm $TAG composer1 diagnose
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
}

@test "composer1 is version 1.x" {
  run docker run --rm $TAG composer1 --version
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "Composer version 1."* ]]
}

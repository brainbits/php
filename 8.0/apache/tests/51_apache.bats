#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "run dir was created" {
  run docker run --rm $TAG ls -d /run/apache2
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "/run/apache2"* ]]
}

@test "default value for apache DocumentRoot is '/app'" {
  run docker run --rm $TAG egrep "^DocumentRoot .+" /etc/apache2/httpd.conf
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "DocumentRoot \"/app\""* ]]
}

@test "default value for apache user is 'apache'" {
  run docker run --rm $TAG egrep "^User .+" /etc/apache2/httpd.conf
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "User \"apache\""* ]]
}

@test "default value for apache group is 'apache'" {
  run docker run --rm $TAG egrep "^Group .+" /etc/apache2/httpd.conf
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "Group \"apache\""* ]]
}

@test "apache access log is symlinked to stdout" {
  run docker run --rm $TAG readlink /var/log/apache2/access.log
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "/dev/stdout"* ]]
}

@test "apache access log is symlinked to stdout" {
  run docker run --rm $TAG readlink /var/log/apache2/error.log
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "/dev/stderr"* ]]
}

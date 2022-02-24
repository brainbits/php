#!/usr/bin/env bats
set -e

setup() {
if [ -z ${TAG+x} ]; then
echo 'TAG environment variable not set'
exit 1
fi
}

@test "default state for php-xdebug is inactive" {
run docker run --rm $TAG php --ri xdebug
echo "status: $status"
echo "output: $output"
[ "$status" -eq 1 ]
}

@test "setting env var PHP_ACTIVATE_XDEBUG to 1 activates php-xdebug" {
run docker run --rm -e PHP_ACTIVATE_XDEBUG=1 $TAG php --ri xdebug
echo "status: $status"
echo "output: $output"
[ "$status" -eq 0 ]
}

@test "setting env var PHP_XDEBUG_REMOTE_HOST to localhost sets php ini setting xdebug.client_host" {
run docker run --rm -e PHP_ACTIVATE_XDEBUG=1 -e PHP_XDEBUG_REMOTE_HOST=localhost $TAG bash -c 'php -i | grep xdebug.client_host'
echo "status: $status"
echo "output: $output"
[ "$status" -eq 0 ]
[[ "$output" == "xdebug.client_host => localhost => localhost"* ]]
}

@test "setting env var PHP_XDEBUG_REMOTE_LOG to path sets php ini setting xdebug.log" {
run docker run --rm -e PHP_ACTIVATE_XDEBUG=1 -e PHP_XDEBUG_REMOTE_LOG=/tmp/test.log $TAG bash -c 'php -i | grep xdebug.log'
echo "status: $status"
echo "output: $output"
[ "$status" -eq 0 ]
[[ "$output" == "xdebug.log => /tmp/test.log => /tmp/test.log"* ]]
}

@test "setting env var PHP_XDEBUG_REMOTE_LOG to 1 sets php ini setting xdebug.log" {
run docker run --rm -e PHP_ACTIVATE_XDEBUG=1 -e PHP_XDEBUG_REMOTE_LOG=1 $TAG bash -c 'php -i | grep xdebug.log'
echo "status: $status"
echo "output: $output"
[ "$status" -eq 0 ]
[[ "$output" == "xdebug.log => /tmp/xdebug.log => /tmp/xdebug.log"* ]]
}

@test "setting env var PHP_XDEBUG_REMOTE_LOG to 0 sets php ini setting xdebug.log" {
run docker run --rm -e PHP_ACTIVATE_XDEBUG=1 -e PHP_XDEBUG_REMOTE_LOG=0 $TAG bash -c 'php -i | grep xdebug.log'
echo "status: $status"
echo "output: $output"
[ "$status" -eq 0 ]
[[ "$output" == "xdebug.log => no value => no value"* ]]
}

@test "setting env var PHP_ACTIVATE_XDEBUG to 0 deactivates php-xdebug" {
run docker run --rm -e PHP_ACTIVATE_XDEBUG=0 $TAG php --ri xdebug
echo "status: $status"
echo "output: $output"
[ "$status" -eq 1 ]
}

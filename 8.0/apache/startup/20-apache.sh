#!/bin/bash
set -e

log_command "remove apache pid"
find /run/apache2 -type f -exec rm {} \;

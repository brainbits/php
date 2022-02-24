#!/bin/bash
set -e

function log_script {
    if [ -n "$VERBOSE" ]; then
        echo "[startup] . $1"
    fi
}

for file in `ls -1 /usr/bin/startup/*.sh`; do
    log_script $file
    source $file
done

exec "$@"

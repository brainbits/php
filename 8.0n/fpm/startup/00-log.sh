#!/bin/bash
set -e

function log_command {
    if [ -n "$VERBOSE" ]; then
        echo "[startup]   > $1"
    fi
}
function log_var {
    if [ -n "$VERBOSE" ]; then
        echo "[startup]   $ $1"
    fi
}
function log_setting {
    if [ -n "$VERBOSE" ]; then
        echo "[startup]   % $1"
    fi
}
function log_deprecation {
    if [ -n "$VERBOSE" ]; then
        echo "[startup]   ! $1"
    fi
}

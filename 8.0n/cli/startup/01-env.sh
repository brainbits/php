#!/bin/bash
set -e

function set_env {
    log_var "env $1=$2"
    export $1=$2
}

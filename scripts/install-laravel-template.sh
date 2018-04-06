#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Clone Laravel template repo into `laravel-template/` for easily upgrading the
# test app.
################################################################################

exec git clone git@github.com:laravel/laravel.git laravel-template

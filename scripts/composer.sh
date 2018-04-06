#!/bin/bash
set -o nounset -o pipefail -o errexit
workdir="$PWD"
cd "$(dirname "$0")/.."

################################################################################
# Run Composer.
################################################################################

mkdir -p tmp

scripts/docker-compose.sh run --rm -w "/app${workdir##$PWD}" composer composer "$@"

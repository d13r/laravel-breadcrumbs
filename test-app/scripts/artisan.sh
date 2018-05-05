#!/bin/bash
set -o nounset -o pipefail -o errexit
workdir="$PWD"
cd "$(dirname "$0")/../.."

################################################################################
# Run Artisan.
################################################################################

exec scripts/docker-compose.sh run --rm -w "/app${workdir##$PWD}" test-app-72 /app/test-app/artisan "$@"

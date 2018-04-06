#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run phpDocumentor.
################################################################################

scripts/docker-compose.sh run --rm phpdoc "$@"

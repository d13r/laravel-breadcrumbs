#!/bin/bash
set -o nounset -o pipefail -o errexit
workdir="$PWD"
cd "$(dirname "$0")/.."

################################################################################
# Delete all generated files.
################################################################################

scripts/docker-compose.sh down --rmi all -v

rm -rfv \
    composer.lock \
    docs/ \
    laravel-template/ \
    test-app/composer.lock \
    test-app/storage/framework/*/* \
    test-app/storage/logs/* \
    test-app/vendor/ \
    test-coverage/ \
    tmp/ \
    vendor/

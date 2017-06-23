#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit with code coverage (requires Xdebug).
################################################################################

exec php -d xdebug.coverage_enable=On vendor/bin/phpunit --coverage-html test-coverage "$@"

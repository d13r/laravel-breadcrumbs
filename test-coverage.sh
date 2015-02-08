#!/bin/bash
set -eu
cd $(dirname $0)

php -d xdebug.coverage_enable=On vendor/bin/phpunit --coverage-html test-coverage "$@"

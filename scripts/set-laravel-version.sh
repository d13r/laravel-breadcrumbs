#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Install the specified version of Laravel for testing.
#
# Usage:   `t set-laravel-version <version>`
# Example: `t set-laravel-version 5.7`
#
# This will update `project/composer.json` and install it.
# *Do not commit the changes.*
################################################################################

if [ $# -lt 1 ]; then
    echo "Usage: set-laravel-version <version>"
    exit 1
fi

version="${1:-}"

# Two separate steps because of: https://github.com/composer/composer/issues/7261
composer require --dev "laravel/framework:${version}.*" --no-update
composer update

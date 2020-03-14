#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Install all development dependencies.
################################################################################

header() {
    echo -ne "\e[94m"
    echo -n "$@"
    echo -e "\e[0m"
}

# Install/update dependencies
if [ -d vendor ]; then
    header 'Updating dependencies...'
else
    header 'Installing dependencies...'
fi

composer update

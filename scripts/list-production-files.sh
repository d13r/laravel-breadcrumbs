#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# List files that will be included in a production install.
# All files must be committed before running this script.
################################################################################

git archive HEAD | tar -t

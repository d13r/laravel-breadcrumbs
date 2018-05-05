#!/bin/bash
set -o nounset -o pipefail -o errexit

################################################################################
# Run Docker Compose.
################################################################################

# Make sure these directories exist so Docker Compose doesn't create them as root
mkdir -p docs test-coverage tmp/grip

# Ensure generated files are owned by the current user not root
export UID
export GID="$(id -g)"

exec docker-compose "$@"

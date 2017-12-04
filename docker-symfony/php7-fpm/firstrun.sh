#!/bin/bash

set -euxo pipefail

composer=$(which composer)
php=$(which php)
sf="${php} bin/console"

# install Symfony3 and dependencies
${composer} install --dev
# create database structure
${sf} doctrine:schema:update --force
# load the testing database
${sf} doctrine:fixtures:load


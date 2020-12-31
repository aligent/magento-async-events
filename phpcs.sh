#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

../../../../vendor/bin/phpcs -s --standard=Magento2 Api Model Service \
	&& echo -e "${GREEN}No PHP code sniff violations found${NC}"


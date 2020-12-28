# Default shell to use
SHELL := bash

# Reuse the same shell instance within a target
.ONESHELL:

# Set bash to fail immediately (-e), to error on unset variables (-u) and to fail on piped commands (pipefail)
.SHELLFLAGS := -eu -o pipefail -c

# Delete any generated target on failure
.DELETE_ON_ERROR:

# Make flags
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules

# Shortcut for docker-compose command
COMPOSE = docker-compose

# Shortcut for docker run on the app container with all dependencies already up
RUN_APP = $(COMPOSE) run -e COMPOSER_MEMORY_LIMIT=-1 --rm app -d memory_limit=-1

INFECTION_VERSION=0.16.3

# Default target when run with just 'make'
default: ci-tests

# Main docker image build
.PHONY: build
build:
	@$(COMPOSE) build --parallel
	$(COMPOSE) up --no-start --remove-orphans

.PHONY: install-deps
install-deps: build
	$(RUN_APP) /usr/local/bin/composer install --ignore-platform-req=php

.PHONY: ci-infection
ci-infection: install-deps
	wget https://github.com/infection/infection/releases/download/$(INFECTION_VERSION)/infection.phar
	sudo chown -R `whoami`: var/cache
	php infection.phar --min-msi=80 --min-covered-msi=70 --threads=4 --show-mutations --only-covered
	rm -rf infection.phar

.PHONY: ci-analysis
ci-analysis: install-deps
	$(RUN_APP) /usr/local/bin/composer psalm

.PHONY: ci-tests
ci-tests: install-deps
	$(RUN_APP) /usr/local/bin/composer unit-tests

.PHONY: database
database:
	$(COMPOSE) run --rm wait
	$(RUN_APP) /usr/local/bin/composer clear-db

.PHONY: services
services: build
	$(COMPOSE) up -d --remove-orphans

.PHONY: attach
attach: build
	$(COMPOSE) run --rm app sh

.PHONY: run
run:
	$(COMPOSE) -f docker-compose.yaml -f docker-compose.full-docker.yaml.dist up -d --remove-orphans

.PHONY: stop
stop:
	$(COMPOSE) -f docker-compose.yaml -f docker-compose.full-docker.yaml.dist stop

.PHONY: ps
ps:
	$(COMPOSE) -f docker-compose.yaml -f docker-compose.full-docker.yaml.dist ps

.PHONY: run-locally
run-locally: services
	symfony serve -d --allow-http --port=8080 --no-tls

.PHONY: stop-locally
stop-locally:
	$(COMPOSE) stop
	symfony server:stop

.PHONY: ps-locally
ps-locally:
	$(COMPOSE) ps

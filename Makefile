.DEFAULT_GOAL:=default
PHP ?= php -d memory_limit=-1
SHELL_COMMAND ?= ash
SHELL_IMAGE ?= php:8.1.19-cli-alpine3.16
SHELL_PACKAGES ?= make wget


ifeq ($(APP_ENV), $(filter $(APP_ENV),dev test))
	COMPOSER_PARAMS ?=
else ifeq ($(APP_ENV), prod)
	COMPOSER_PARAMS ?= --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-suggest --ansi --classmap-authoritative
endif

# PHONY GOALS

clean:
	@echo -n "This will remove all uncommited changes. Are you sure? [y/N] " && read ans && [ $${ans:-N} = y ]
	git clean -fdx
.PHONY: clean

default: install build
.PHONY: default

install: composer.phar
	$(PHP) composer.phar install $(COMPOSER_PARAMS)
.PHONY: install

start_shell:
	docker run --rm -it -v $(shell pwd):/app $(SHELL_IMAGE) /bin/sh -c '\
		addgroup -g $(shell id -g) app_group && \
		adduser -u $(shell id -u) -D -G app_group app_user && \
		( [ -n "$(SHELL_PACKAGES)" ] && apk add $(SHELL_PACKAGES) || true ) && \
		cd /app && su app_user -c $(SHELL_COMMAND) \
	' || true
.PHONY: start_shell

update:
	$(PHP) composer.phar update 
.PHONY: update

# NON PHONY GOALS

composer.phar:
	wget https://getcomposer.org/installer -O - -q | php -- --quiet

DOCKER_COMPOSE_DIR=./
DOCKER_COMPOSE_FILE=$(DOCKER_COMPOSE_DIR)/docker-compose.yml
DOCKER_COMPOSE=docker-compose -f $(DOCKER_COMPOSE_FILE) --project-directory $(DOCKER_COMPOSE_DIR)

DEFAULT_GOAL := help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: d-up
d-up: ## Start all docker containers. To only start one container, use CONTAINER=<service>
	$(DOCKER_COMPOSE) up -d $(CONTAINER)

.PHONY: d-down
d-down: ## Stop all docker containers. To only stop one container, use CONTAINER=<service>
	$(DOCKER_COMPOSE) down $(CONTAINER)

.PHONY: d-bash
d-bash: ## Execute `/bin/sh` into the `php` service
	 $(DOCKER_COMPOSE) exec php /bin/sh

.PHONY: d-test
d-test: ## Run `phpunit` at `/www/tests` directory (using `/www/tests/bootstrap.php` file).
	docker-compose exec php /www/vendor/bin/phpunit --bootstrap /www/tests/bootstrap.php /www/tests --color=always

.PHONY: d-composer
d-composer: ## Run `composer install` from container's image, over composer.json
	docker-compose exec php /bin/sh -c "cd /www && composer install"

.PHONY: phpstan
phpstan: ## Execute `phpstan` from his official docker image, over the specified DIR directory (optional)
	docker run --rm -v $(PWD):/www ghcr.io/phpstan/phpstan --level=7 analyse /www/src/$(DIR) --autoload-file /www/vendor/autoload.php

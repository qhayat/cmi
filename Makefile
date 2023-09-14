DC='docker-compose'
DOCKER='docker'
PHP_CONTAINER='cmi'

up:
	@$(DC) up -d

down:
	@$(DC) down

restart: down up

build:
	@$(DC) up -d --build

app:
	@$(DC) exec -it $(PHP_CONTAINER) sh

composer:
	@$(DC) exec $(PHP_CONTAINER) composer install

clear-cache:
	@$(DC) exec $(PHP_CONTAINER) php bin/console c:c

jwt-keypair:
	@$(DC) exec $(PHP_CONTAINER) php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction

# DATABASE
db-diff:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:m:diff

db-dump-sql:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:s:u --dump-sql

db-migrate:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:m:m --no-interaction

db-delete:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:d:d --force --if-exists --no-interaction

db-create:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:d:c --if-not-exists --no-interaction

db-load-fixtures:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:f:l --no-interaction

db-reset: db-delete db-create db-migrate db-load-fixtures

test-db-init:
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:d:d --force --if-exists --env=test
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:d:c --if-not-exists --env=test
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:m:m --no-interaction --env=test
	@$(DC) exec $(PHP_CONTAINER) php bin/console d:f:l --no-interaction --env=test

phpstan:
	@$(DC) exec $(PHP_CONTAINER) ./vendor/bin/phpstan

phpunit:
	@$(DC) exec $(PHP_CONTAINER) ./vendor/bin/phpunit

php-cs-fixer-dry-run:
	@$(DC) exec $(PHP_CONTAINER) ./vendor/bin/php-cs-fixer fix -vvv --allow-risky=yes --dry-run

php-cs-fixer:
	@$(DC) exec $(PHP_CONTAINER) ./vendor/bin/php-cs-fixer fix -vvv --allow-risky=yes

qa: phpstan php-cs-fixer-dry-run phpunit

env:
	@$(DC) exec $(PHP_CONTAINER) cp .env.dist .env

init: build env composer jwt-keypair db-reset


docker-compose-build:
	env UID=$(shell id -u) docker-compose build
docker-compose-up:
	env UID=$(shell id -u) docker-compose up -d
	make composer-install
	make doctrine-migration-migrate
	make symfony-cache-clear
docker-compose-down:
	env UID=$(shell id -u) docker-compose down
terminal-php:
	env UID=$(shell id -u) docker-compose exec php sh
composer-install:
	env UID=$(shell id -u) docker-compose exec php composer install
composer-update:
	env UID=$(shell id -u) docker-compose exec php composer update
doctrine-migration-diff:
	env UID=$(shell id -u) docker-compose exec php bin/console doctrine:migrations:diff
doctrine-migration-migrate:
	env UID=$(shell id -u) docker-compose exec php bin/console doctrine:migrations:migrate -n
	env UID=$(shell id -u) docker-compose exec php bin/console doctrine:migrations:migrate -n -e test
test-behat:
	env UID=$(shell id -u) docker-compose exec -e APP_ENV=test php vendor/bin/behat
symfony-cache-clear:
	env UID=$(shell id -u) docker-compose exec php bin/console cache:clear
	env UID=$(shell id -u) docker-compose exec -e APP_ENV=test php bin/console cache:clear
static-cs-check:
	env UID=$(shell id -u) docker-compose exec php vendor/bin/php-cs-fixer check --config=.php-cs-fixer.dist.php -v --stop-on-violation --using-cache=no
static-cs-fix:
	env UID=$(shell id -u) docker-compose exec php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
static-phpstan:
	env UID=$(shell id -u) docker-compose exec php vendor/bin/phpstan analyse src
static-deptrac:
	env UID=$(shell id -u) docker-compose exec php vendor/bin/deptrac analyse
ci-commit-check:
	make static-phpstan
	make static-cs-check
	make static-deptrac
	make test-behat
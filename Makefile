docker-compose-build:
	env UID=$(shell id -u) docker-compose build
docker-compose-up:
	env UID=$(shell id -u) docker-compose up -d
docker-compose-down:
	env UID=$(shell id -u) docker-compose down
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
	env UID=$(shell id -u) docker-compose exec -e APP_ENV=test php vendor/bin/behat -vv
symfony-cache-clear:
	env UID=$(shell id -u) docker-compose exec php bin/console cache:clear
	env UID=$(shell id -u) docker-compose exec -e APP_ENV=test php bin/console cache:clear
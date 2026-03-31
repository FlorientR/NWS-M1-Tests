.PHONY: install test test-unit test-integration test-application shell

install:
	docker compose run --rm php composer install
	docker compose run --rm php mkdir -p var
	docker compose run --rm php php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker compose up -d web

up:
	docker compose up -d web

down:
	docker compose down

test: install
	docker compose run --rm php php vendor/bin/phpunit --testdox

test-unit:
	docker compose run --rm php php vendor/bin/phpunit --testdox --testsuite=Unit

test-integration:
	docker compose run --rm php php vendor/bin/phpunit --testdox --testsuite=Integration

test-application:
	docker compose run --rm php php vendor/bin/phpunit --testdox --testsuite=Application

shell:
	docker compose run --rm php bash
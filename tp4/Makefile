.PHONY: install test test-unit test-integration test-application shell

install:
	docker compose run --rm php composer install
	docker compose run --rm php mkdir -p var
	docker compose run --rm php php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker compose up -d

up:
	docker compose up -d

down:
	docker compose down

test:
	docker compose run --rm php php vendor/bin/phpunit --testdox

test-unit:
	docker compose run --rm php php vendor/bin/phpunit --testdox --testsuite=Unit

test-integration:
	docker compose run --rm php php vendor/bin/phpunit --testdox --testsuite=Integration

test-application:
	docker compose run --rm php php vendor/bin/phpunit --testdox --testsuite=Application

shell:
	docker compose run --rm php bash

## Lance les tests séquentiellement (référence de temps)
test-seq:
	@echo "=== Tests séquentiels (phpunit) ==="
	@docker compose run --rm php bash -c "time php vendor/bin/phpunit --testdox"

## Lance les tests en parallèle avec ParaTest
test-parallel:
	@echo "=== Tests parallèles (paratest) ==="
	@docker compose run --rm php bash -c "time php vendor/bin/paratest --processes=4 --testdox"

## Alias pratique
test: test-parallel

## Rapport de couverture HTML
coverage:
	docker compose run --rm php php vendor/bin/phpunit --coverage-html var/coverage-report
	@echo "Rapport disponible dans var/coverage-report/index.html"
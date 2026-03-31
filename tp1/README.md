# TP1 — La Pyramide des Tests & le Pattern AAA

## Objectif

Découvrir les 3 niveaux de la pyramide de tests (Unitaire, Intégration, Applicatif)
et rédiger chaque type de test en suivant le pattern **Arrange / Act / Assert**.

## Lancer les tests

```bash
# Installation des dépendances
make install

# Tous les tests
make test

# Par type
make test-unit
make test-integration
make test-application
```

## Structure des tests

```
tests/
├── Unit/
│   └── ShippingCalculatorTest.php   ← Tests sans Symfony ni BDD
├── Integration/
│   └── ShippingRateRepositoryTest.php ← KernelTestCase + SQLite
└── Application/
    └── CartControllerTest.php        ← WebTestCase (HTTP simulé)
```

## Aides et docs
- [Symfony](https://symfony.com/doc/7.4/testing.html)
- [PHPUnit](https://docs.phpunit.de/en/10.5/writing-tests-for-phpunit.html)

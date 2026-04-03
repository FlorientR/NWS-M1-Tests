# TP4 — Industrialisation et CI/CD

## Objectif

Optimiser une suite de tests existante et automatiser la validation via GitHub Actions.

## Commandes disponibles

```bash
make install          # Installe les dépendances

make test-seq         # Tests séquentiels  (phpunit)  — mesure le temps
make test-parallel    # Tests en parallèle (paratest) — mesure le temps
make test             # Alias de test-parallel

make coverage         # Génère var/coverage-report/index.html
make shell            # Shell dans le container PHP
```

## Exercice 1 — Performance : ParaTest

```bash
# Mesurez et comparez :
make test-seq       # Notez le temps affiché
make test-parallel  # Comparez avec --processes=4
```

Questions :
- Quel est le gain en secondes ?
- Tous les tests passent-ils en parallèle ? (attention aux conflits BDD)
- Quel paramètre `--processes` est optimal sur votre machine ?

## Exercice 2 — Qualimétrie : Couverture de code

```bash
make coverage
# Ouvrez var/coverage-report/index.html dans votre navigateur
```

Questions :
- Quel est le taux de couverture global ?
- Quelle classe est la moins couverte ?
- Ajoutez des tests pour atteindre 80% de couverture.

## Exercice 3 — Automatisation : GitHub Actions

Ouvrez `.github/workflows/ci.yml`. Le fichier contient 3 blocs `TODO` :

| TODO   | Tâche                                            |
|--------|--------------------------------------------------|
| TODO 1 | Décommenter et configurer le service WireMock    |
| TODO 2 | Remplacer `phpunit` par `paratest --processes=2` |
| TODO 3 | Ajouter l'upload de couverture vers Codecov      |

**Défi final** : faites une Pull Request sur votre repo. Le pipeline doit être **vert** ✅.
Si la CI est rouge, le TP n'est pas validé.

## Architecture des tests

```
tests/
├── Unit/                       ← Sans BDD, sans réseau (~50ms)
│   ├── ShippingCalculatorTest.php
│   ├── LoyaltyPointsServiceTest.php
│   └── WeatherServiceTest.php
├── Integration/                ← BDD SQLite + WireMock (~500ms)
│   ├── ShippingRateRepositoryTest.php
│   └── WeatherServiceWireMockTest.php
└── Application/                ← HTTP simulé (~200ms)
    └── CartControllerTest.php
```

## Structure du projet complet

```
tp4-cicd/
├── .github/workflows/ci.yml   ← Pipeline à compléter
├── docker-compose.yml          ← PHP + WireMock
├── Makefile                    ← Commandes raccourcies
├── phpunit.xml                 ← Config PHPUnit + ParaTest
├── src/
│   ├── Entity/                 ← ShippingRate, User
│   ├── Service/                ← ShippingCalculator, WeatherService, LoyaltyPoints
│   └── ...
├── tests/
│   ├── Unit/ Integration/ Application/
└── wiremock/mappings/          ← Simulations API météo
```
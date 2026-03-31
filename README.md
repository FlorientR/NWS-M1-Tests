# NWS M1 — Tests Unitaires : 4 TPs Symfony 7.2 + PHPUnit 11

## Structure du dépôt

```
.
├── tp1/   TP1 — La Pyramide des tests & Pattern AAA
├── tp2/   TP2 — Isolation et Mocking avec WireMock
├── tp3/        TP3 — Design de Code et TDD (Red-Green-Refactor)
└── tp4/       TP4 — Industrialisation et CI/CD
```

## Démarrage rapide

Chaque TP est autonome. Pour lancer un TP :

```bash
cd tp1    # (ou tp2, tp3, tp4)
make install       # Installe les dépendances via Docker
make test          # Lance tous les tests
```

## Prérequis

- Docker Desktop (ou Docker Engine + Compose v2)
- Make

## Tableau récapitulatif

| TP  | Thème                     | Commande rapide | Particularité      |
|-----|---------------------------|-----------------|--------------------|
| tp1 | Pyramide & AAA            | `make test`     | Premiers tests     |
| tp2 | WireMock & Isolation      | `make test`     | Container WireMock |
| tp3 | TDD Red-Green-Refactor    | `make test`     | Code à implémenter |
| tp4 | ParaTest & GitHub Actions | `make test`     | CI à compléter     |

## Technologies

- **PHP 8.3** + **Symfony 7.2**
- **PHPUnit 11** + **ParaTest 7** (TP4)
- **DAMA/DoctrineTestBundle** (transactions rollback)
- **WireMock 3** (simulation HTTP)
- **SQLite** (base de données de test)
- **PCOV** (couverture de code)

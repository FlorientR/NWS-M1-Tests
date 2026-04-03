# TP3 — Design de Code et TDD (Red-Green-Refactor)

## Objectif

Développer un système de **Points de Fidélité** de zéro en suivant strictement
le cycle TDD : **Rouge → Vert → Refactorer**.

## Le cycle à suivre

```
ROUGE  : Les tests existent, mais le code n'est pas implémenté
  ↓
VERT   : Implémentez le code minimal pour que les tests passent
  ↓
REFACT : Améliorez le code sans casser les tests
  ↓        (ajout de la règle weekend)
ROUGE  : Nouveaux tests pour la nouvelle règle
  ↓        ...et on recommence
```

## Lancer les tests

```bash
make install          # Dépendances
make test-unit        # Phase RED/GREEN/REFACTOR (rapide, sans BDD)
make test-integration # Phase PERSISTANCE (avec BDD)
make test             # Tout
```

## Fichiers à modifier

| Fichier                                              | Quoi faire                     |
|------------------------------------------------------|--------------------------------|
| `src/Service/LoyaltyPointsService::earnPoints()`     | Phase GREEN : 1€ = 1 point     |
| `src/Service/LoyaltyPointsService::earnPoints()`     | Phase REFACTOR : bonus weekend |
| `src/Service/LoyaltyPointsService::earnAndPersist()` | Phase PERSISTANCE              |

## Règles métier à implémenter

- 1€ dépensé = 1 point (arrondi inférieur)
- Le week-end (samedi + dimanche) : points **doublés**
- Montant négatif → `InvalidArgumentException`

## Astuce : simuler la date avec ClockInterface

```php
// Dans les tests — injecter une fausse date
$clock->method('now')->willReturn(new \DateTimeImmutable('2024-01-06')); // samedi

// Dans le service — utiliser l'horloge injectée
$dayOfWeek = (int) $this->clock->now()->format('N'); // 6=sam, 7=dim
```
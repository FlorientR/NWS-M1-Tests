# TP2 — Isolation et Mocking avec WireMock

## Objectif

Tester un service qui dépend d'une API externe **sans connexion internet**,
en utilisant WireMock comme serveur HTTP de simulation.

## Architecture

```
[Tests PHPUnit] --> [WeatherService] --> [WireMock :8080] (simule l'API météo)
```

## Lancer les tests

```bash
make install          # Installe les dépendances
make test             # Lance WireMock + tous les tests
make test-unit        # Tests unitaires seuls (MockHttpClient, rapide)
make test-integration # Tests avec WireMock (nécessite Docker)
```

## Mappings WireMock disponibles

| URL                       | Réponse              |
|---------------------------|----------------------|
| `/weather?city=Paris`     | 200 Sunny            |
| `/weather?city=Brest`     | 200 Storm            |
| `/weather?city=Error`     | 500 Server Error     |
| `/weather?city=Timeout`   | 200 (délai 30 s)     |

## Exercices

1. **Exercice 1** — Ajoutez un mapping pour Lyon (condition Cloudy)
2. **Exercice 2** — Vérifiez que la tempête bloque la livraison (intégration)
3. **Exercice 3** — Implémentez la gestion d'erreur dans `WeatherService`
   et validez avec le mapping `weather-error-500.json`
4. **Exercice 4** — Implémentez un controller qui demande la ville et affiche le résultat, avec gestion des exceptions.
5. **Exercice 5** — Ajoutez un mapping pour un ville inconnue (404)
6. **Exercice 6** — Réalisez un test applicatif sur le controller pour tester le comportement de l'application en cas de 200, 500 et 400.
<?php

namespace App\Tests\Integration;

use App\Exception\WeatherServiceException;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * TP2 - Tests d'INTÉGRATION : WeatherService + WireMock.
 *
 * Ces tests utilisent le vrai HttpClient Symfony qui appelle WireMock
 * (container Docker). L'URL est configurée via WEATHER_API_URL dans .env.test.
 *
 * Prérequis :
 *   docker compose up -d wiremock
 *   (ou `make test-integration`)
 *
 * -----------------------------------------------------------------------
 *  EXERCICE 1 : Ajoutez un mapping WireMock pour Lyon avec condition "Cloudy".
 *               Écrivez le test testGetConditionForLyon().
 *
 *  EXERCICE 2 : Vérifiez que le mapping weather-storm.json renvoie bien
 *               une condition "Storm" pour Brest.
 *
 *  EXERCICE 3 : Implémentez la gestion d'erreur dans WeatherService,
 *               puis validez avec testThrowsExceptionOnServerError().
 *               Ce test doit passer SANS connexion internet — grâce à WireMock.
 * -----------------------------------------------------------------------
 */
class WeatherServiceWireMockTest extends KernelTestCase
{
    private WeatherService $weatherService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->weatherService = self::getContainer()->get(WeatherService::class);
    }

    public function testGetCurrentConditionForParis(): void
    {
        // Act — WireMock intercepte la requête (mapping weather-sunny.json)
        $condition = $this->weatherService->getCurrentCondition('Paris');

        // Assert
        self::assertSame('Sunny', $condition);
    }

    public function testDeliveryIsBlockedDuringStormInBrest(): void
    {
        // Act — WireMock renvoie condition "Storm" (mapping weather-storm.json)
        $possible = $this->weatherService->isDeliveryPossible('Brest');

        // Assert
        self::assertFalse($possible, 'La livraison doit être bloquée par temps de tempête');
    }

    public function testDeliveryIsPossibleWhenSunny(): void
    {
        self::assertTrue($this->weatherService->isDeliveryPossible('Paris'));
    }

    /**
     * EXERCICE 3 — Ce test doit échouer tant que WeatherService
     * ne lève pas WeatherServiceException pour les codes 5xx.
     */
    public function testThrowsExceptionOnServerError(): void
    {
        // WeatherServiceException doit être levée
        // WireMock renvoie 500 pour city=Error (mapping weather-error-500.json)
        $this->expectException(WeatherServiceException::class);

        $this->weatherService->getCurrentCondition('Error');
    }

    // ── TODO EXERCICE 1 ──────────────────────────────────────────────────────
    // Créer wiremock/mappings/weather-cloudy.json pour Lyon
     public function testGetConditionForLyon(): void
     {
         // Act — WireMock renvoie condition "Storm" (mapping weather-storm.json)
         $condition = $this->weatherService->getCurrentCondition('Lyon');

         // Assert
         self::assertSame('Cloudy', $condition);
     }
}

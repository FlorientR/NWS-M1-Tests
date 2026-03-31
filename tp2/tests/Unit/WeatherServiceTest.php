<?php

namespace App\Tests\Unit;

use App\Exception\WeatherServiceException;
use App\Service\WeatherService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * TP2 - Tests UNITAIRES : WeatherService avec MockHttpClient.
 *
 * Ici on n'utilise PAS WireMock : on remplace le vrai client HTTP
 * par un MockHttpClient de Symfony. Cela permet des tests ultra-rapides
 * sans démarrer aucun container.
 *
 * -----------------------------------------------------------------------
 *  EXERCICE 1 : Complétez testGetCurrentConditionReturnsStorm.
 *               Créez un MockResponse avec le JSON approprié.
 *
 *  EXERCICE 2 : Après avoir implémenté la gestion d'erreur dans
 *               WeatherService::getCurrentCondition(), ajoutez un test
 *               qui vérifie que WeatherServiceException est levée
 *               pour un code HTTP 500.
 *
 *  EXERCICE 3 : Testez isDeliveryPossible() pour les conditions
 *               "Sunny", "Storm" et "Blizzard".
 * -----------------------------------------------------------------------
 */
class WeatherServiceTest extends TestCase
{
    public function testGetCurrentConditionReturnsSunny(): void
    {
        // Arrange
        $mockResponse = new MockResponse(
            json_encode(['city' => 'Paris', 'condition' => 'Sunny']),
            ['http_code' => 200]
        );
        $httpClient = new MockHttpClient($mockResponse);
        $service    = new WeatherService($httpClient, 'http://fake-api');

        // Act
        $condition = $service->getCurrentCondition('Paris');

        // Assert
        self::assertSame('Sunny', $condition);
    }

    public function testIsDeliveryPossibleWhenSunny(): void
    {
        $mockResponse = new MockResponse(
            json_encode(['condition' => 'Sunny']),
            ['http_code' => 200]
        );
        $httpClient = new MockHttpClient($mockResponse);
        $service    = new WeatherService($httpClient, 'http://fake-api');

        self::assertTrue($service->isDeliveryPossible('Paris'));
    }

    public function testIsDeliveryBlockedWhenStorm(): void
    {
        $mockResponse = new MockResponse(
            json_encode(['condition' => 'Storm']),
            ['http_code' => 200]
        );
        $httpClient = new MockHttpClient($mockResponse);
        $service    = new WeatherService($httpClient, 'http://fake-api');

        self::assertFalse($service->isDeliveryPossible('Brest'));
    }

    // ── TODO EXERCICE 1 ──────────────────────────────────────────────────────
    // public function testGetCurrentConditionReturnsStorm(): void { ... }

    // ── TODO EXERCICE 2 ──────────────────────────────────────────────────────
    // public function testThrowsExceptionOnHttpError500(): void { ... }
}

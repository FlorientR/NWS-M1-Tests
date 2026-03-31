<?php

namespace App\Tests\Integration;

use App\Exception\WeatherServiceException;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * TP4 - Test d'intégration WeatherService + WireMock.
 *
 * EXERCICE TP4 :
 *   1. Lancez `make test-seq`    et notez le temps total.
 *   2. Lancez `make test-parallel` et comparez.
 *   Quel gain observez-vous ?
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
        self::assertSame('Sunny', $this->weatherService->getCurrentCondition('Paris'));
    }

    public function testDeliveryBlockedDuringStorm(): void
    {
        self::assertFalse($this->weatherService->isDeliveryPossible('Brest'));
    }

    public function testThrowsExceptionOnServerError(): void
    {
        $this->expectException(WeatherServiceException::class);
        $this->weatherService->getCurrentCondition('Error');
    }
}

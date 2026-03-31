<?php

namespace App\Tests\Unit;

use App\Exception\WeatherServiceException;
use App\Service\WeatherService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * TP4 - Tests unitaires WeatherService (version complète du TP2).
 */
class WeatherServiceTest extends TestCase
{
    public function testGetCurrentConditionReturnsSunny(): void
    {
        $mock    = new MockHttpClient(new MockResponse(json_encode(['condition' => 'Sunny']), ['http_code' => 200]));
        $service = new WeatherService($mock, 'http://fake-api');
        self::assertSame('Sunny', $service->getCurrentCondition('Paris'));
    }

    public function testIsDeliveryBlockedWhenStorm(): void
    {
        $mock    = new MockHttpClient(new MockResponse(json_encode(['condition' => 'Storm']), ['http_code' => 200]));
        $service = new WeatherService($mock, 'http://fake-api');
        self::assertFalse($service->isDeliveryPossible('Brest'));
    }

    public function testThrowsWeatherServiceExceptionOn500(): void
    {
        $mock    = new MockHttpClient(new MockResponse('{}', ['http_code' => 500]));
        $service = new WeatherService($mock, 'http://fake-api');
        $this->expectException(WeatherServiceException::class);
        $service->getCurrentCondition('Error');
    }

    public function testIsDeliveryPossibleWhenSunny(): void
    {
        $mock    = new MockHttpClient(new MockResponse(json_encode(['condition' => 'Sunny']), ['http_code' => 200]));
        $service = new WeatherService($mock, 'http://fake-api');
        self::assertTrue($service->isDeliveryPossible('Paris'));
    }
}

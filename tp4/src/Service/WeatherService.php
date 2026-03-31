<?php

namespace App\Service;

use App\Exception\WeatherServiceException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $weatherApiUrl,
    ) {}

    public function getCurrentCondition(string $city): string
    {
        try {
            $response   = $this->httpClient->request('GET', $this->weatherApiUrl . '/weather', ['query' => ['city' => $city]]);
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 500) {
                throw WeatherServiceException::fromHttpStatus($statusCode, $this->weatherApiUrl);
            }

            return $response->toArray()['condition'] ?? 'Unknown';
        } catch (TransportExceptionInterface $e) {
            throw WeatherServiceException::fromTimeout($this->weatherApiUrl);
        }
    }

    public function isDeliveryPossible(string $city): bool
    {
        $condition = $this->getCurrentCondition($city);
        foreach (['Storm', 'Blizzard', 'Hurricane'] as $blocked) {
            if (str_contains($condition, $blocked)) return false;
        }
        return true;
    }
}

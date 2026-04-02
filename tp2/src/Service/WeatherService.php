<?php

namespace App\Service;

use App\Exception\WeatherServiceException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Service qui interroge une API météo externe pour savoir
 * si les conditions permettent une livraison.
 */
class WeatherService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $weatherApiUrl,
    ) {}

    /**
     * Retourne la condition météo actuelle pour une ville.
     *
     * @throws WeatherServiceException si l'API est indisponible
     */
    public function getCurrentCondition(string $city): string
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->weatherApiUrl . '/weather',
                ['query' => ['city' => $city]]
            );

            $statusCode = $response->getStatusCode();

            if ($statusCode >= 500) {
                throw new WeatherServiceException('Weather API is not available');
            }

            $data = $response->toArray();

            return $data['condition'] ?? 'Unknown';

        } catch (TransportExceptionInterface $e) {
            throw new WeatherServiceException('Weather API is not available');
        }
    }

    /**
     * Détermine si la livraison est possible selon la météo.
     *
     * Règle : si la condition contient "Storm", "Blizzard" ou "Hurricane" => bloqué.
     */
    public function isDeliveryPossible(string $city): bool
    {
        $condition = $this->getCurrentCondition($city);

        $blockedConditions = ['Storm', 'Blizzard', 'Hurricane'];

        foreach ($blockedConditions as $blocked) {
            if (str_contains($condition, $blocked)) {
                return false;
            }
        }

        return true;
    }
}

<?php

namespace App\Exception;

/**
 * Levée quand le service météo est indisponible ou retourne une erreur.
 *
 * TP2 - EXERCICE 3 :
 *  Modifiez WeatherService pour lever cette exception quand :
 *  - Le code HTTP de la réponse est >= 500
 *  - Le timeout est dépassé (TransportException)
 *  Puis écrivez le test d'intégration correspondant.
 */
class WeatherServiceException extends \RuntimeException
{
    public static function fromHttpStatus(int $status, string $url): self
    {
        return new self(
            sprintf('Le service météo a retourné une erreur %d pour %s', $status, $url),
            $status
        );
    }

    public static function fromTimeout(string $url): self
    {
        return new self(
            sprintf('Timeout lors de la connexion au service météo : %s', $url)
        );
    }
}

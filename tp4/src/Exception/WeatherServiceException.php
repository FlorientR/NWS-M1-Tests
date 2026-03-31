<?php

namespace App\Exception;

class WeatherServiceException extends \RuntimeException
{
    public static function fromHttpStatus(int $status, string $url): self
    {
        return new self(sprintf('Le service météo a retourné %d pour %s', $status, $url), $status);
    }

    public static function fromTimeout(string $url): self
    {
        return new self(sprintf('Timeout lors de la connexion à %s', $url));
    }
}

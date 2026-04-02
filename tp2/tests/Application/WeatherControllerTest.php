<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testThatWeatherControllerIsSuccessful(): void
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/');

        // Assert
        $this->assertResponseIsSuccessful();
    }

    public function testThatWeatherControllerIsSuccessfulWithSuccessfullCity(): void
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('POST', '/', [
            'city' => 'Paris',
        ]);

        // Assert
        $this->assertResponseIsSuccessful();
    }

    public function testThatWeatherControllerIsSuccessfulWithErrorCity(): void
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('POST', '/', [
            'city' => 'Error',
        ]);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Une erreur est survenue.');
    }

    public function testThatWeatherControllerIsSuccessfulWithUnknownCity(): void
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('POST', '/', [
            'city' => 'Inconnue',
        ]);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Ville inconnue');
    }
}
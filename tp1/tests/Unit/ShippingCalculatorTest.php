<?php

namespace App\Tests\Unit;

use App\Repository\ShippingRateRepository;
use App\Service\ShippingCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests UNITAIRES de ShippingCalculator::calculate().
 *
 * Ces tests vérifient la logique de calcul statique, sans base de données.
 * Le dépôt est remplacé par un mock car calculate() ne l'utilise pas.
 */
class ShippingCalculatorTest extends TestCase
{
    private ShippingCalculator $calculator;

    /** @var ShippingRateRepository&MockObject */
    private ShippingRateRepository $repository;

    protected function setUp(): void
    {
        // Le repository n'est pas utilisé par calculate(), on le mocke juste
        // pour satisfaire le constructeur.
        $this->repository = $this->createMock(ShippingRateRepository::class);
        $this->calculator = new ShippingCalculator($this->repository);
    }

    // -------------------------------------------------------------------------
    // Exemples implémentés
    // -------------------------------------------------------------------------

    /**
     * Un poids nul doit lever une InvalidArgumentException.
     */
    public function testCalculateThrowsExceptionForNonPositiveWeight(): void
    {
        // Arrange
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/strictement positif/');

        // Act
        $this->calculator->calculate(0.0);

        // Assert — géré par expectException() ci-dessus
    }

    /**
     * Un colis léger (1 kg, soit < 5 kg) doit coûter 5,00 €.
     *
     * Règles métier attendues (voir doc-block de ShippingCalculator) :
     *   < 5 kg   => 5,00 €
     *   5-10 kg  => 10,00 €
     *   10-30 kg => 20,00 €
     *   > 30 kg  => 50,00 €
     */
    public function testCalculateForLightPackageShouldReturn5Euros(): void
    {
        // Arrange
        $weight = 1.0;

        // Act
        $result = $this->calculator->calculate($weight);

        // Assert
        $this->assertSame(5.00, $result);
    }

    // -------------------------------------------------------------------------
    // Méthodes à compléter
    // -------------------------------------------------------------------------

    /**
     * Un colis de 7 kg (tranche 5-10 kg) doit coûter 10,00 €.
     */
    public function testCalculateForMediumPackageShouldReturn10Euros(): void
    {
        // TODO : appeler calculate(7.0) et asserter 10.00
        self::markTestSkipped();
    }

    /**
     * Un colis de 15 kg (tranche 10-30 kg) doit coûter 20,00 €.
     */
    public function testCalculateForHeavyPackageShouldReturn20Euros(): void
    {
        // TODO : appeler calculate(15.0) et asserter 20.00
        self::markTestSkipped();
    }

    /**
     * Un colis de 40 kg (> 30 kg) doit coûter 50,00 €.
     */
    public function testCalculateForVeryHeavyPackageShouldReturn50Euros(): void
    {
        // TODO : appeler calculate(40.0) et asserter 50.00
        self::markTestSkipped();
    }

    /**
     * Tester les valeurs exactement aux bornes des tranches (ex. 5.0 kg, 10.0 kg, 30.0 kg).
     * Les tests de frontière (boundary) sont essentiels pour détecter les erreurs off-by-one.
     */
    public function testCalculateAtBoundary5kg(): void
    {
        // TODO : appeler calculate(5.0) — à quelle tranche appartient exactement 5 kg ?
        //        Comparer avec les règles métier dans le doc-block de ShippingCalculator
        self::markTestSkipped();
    }

    /**
     * Un poids infinitésimal (ex. 0.001 kg) doit entrer dans la tranche < 5 kg.
     */
    public function testCalculateForTinyWeightShouldReturn5Euros(): void
    {
        // TODO : appeler calculate(0.001) et asserter 5.00
        self::markTestSkipped();
    }
}

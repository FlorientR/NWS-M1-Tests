<?php

namespace App\Tests\Integration;

use App\Entity\ShippingRate;
use App\Repository\ShippingRateRepository;
use App\Service\ShippingCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * TP4 - Test d'intégration ShippingRateRepository.
 */
class ShippingRateRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface  $em;
    private ShippingRateRepository  $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em         = self::getContainer()->get(EntityManagerInterface::class);
        $this->repository = self::getContainer()->get(ShippingRateRepository::class);
    }

    public function testFindAllReturnsFourRates(): void
    {
        // Les 4 tranches sont insérées par la migration
        $rates = $this->repository->findAllOrderedByWeight();
        self::assertCount(4, $rates);
    }

    public function testFirstRateIsLightPackage(): void
    {
        $rates = $this->repository->findAllOrderedByWeight();
        self::assertSame(5.00, $rates[1]->getPrice());
    }

    public function testLastRateHasNullMaxWeight(): void
    {
        $rates = $this->repository->findAllOrderedByWeight();
        self::assertNull(array_first($rates)->getMaxWeightKg());
    }

    /**
     * Crée et persiste une tranche tarifaire en base.
     */
    private function createRate(float $price, string $label, ?float $maxWeightKg): ShippingRate
    {
        $rate = new ShippingRate($price, $label, $maxWeightKg);
        $this->em->persist($rate);
        $this->em->flush();

        return $rate;
    }

    /**
     * calculateFromDatabase() doit retourner le bon tarif en fonction du poids
     * pour les tranches standard (< 5 kg, 5-10 kg, 10-30 kg, illimité).
     */
    public function testCalculateFromDatabaseReturnsCorrectRateForEachWeightBand(): void
    {
        // Arrange
        $this->createRate(50.00, 'Très lourd (illimité)', null);   // NULL = illimité
        $this->createRate(10.00, 'Moyen (≤ 10 kg)', 10.0);
        $this->createRate(5.00,  'Léger (≤ 5 kg)',   5.0);
        $this->createRate(20.00, 'Lourd (≤ 30 kg)',  30.0);
        $shippingCalculator = self::getContainer()->get(ShippingCalculator::class);

        // Act
        $calculateLessThan5Kg = $shippingCalculator->calculateFromDatabase(0.5);
        $calculateBetween5And10Kg = $shippingCalculator->calculateFromDatabase(5.5);
        $calculateBetween10And30Kg = $shippingCalculator->calculateFromDatabase(15.5);
        $calculateOver30Kg = $shippingCalculator->calculateFromDatabase(30.5);

        // Asert
        $this->assertSame(5.0, $calculateLessThan5Kg);
        $this->assertSame(10.0, $calculateBetween5And10Kg);
        $this->assertSame(20.0, $calculateBetween10And30Kg);
        $this->assertSame(50.0, $calculateOver30Kg);
    }

    /**
     * calculateFromDatabase() avec un poids négatif doit lever une InvalidArgumentException.
     */
    public function testCalculateFromDatabaseThrowsExceptionForNonPositiveWeight(): void
    {
        // Arrange
        $shippingCalculator = self::getContainer()->get(ShippingCalculator::class);

        // Assert
        $this->expectException(\InvalidArgumentException::class);

        // Act
        $shippingCalculator->calculateFromDatabase(-1);
    }

    /**
     * calculateFromDatabase() doit lever une RuntimeException quand aucune tranche
     * n'est configurée en base.
     */
    public function testCalculateFromDatabaseThrowsExceptionWhenNoRatesConfigured(): void
    {
        // Arrange
        $shippingCalculator = self::getContainer()->get(ShippingCalculator::class);
        $rates = $this->repository->findAll();
        foreach ($rates as $rate) {
            $this->em->remove($rate);
        }
        $this->em->flush();

        // Assert
        $this->expectException(\RuntimeException::class);

        // Act
        $shippingCalculator->calculateFromDatabase(5.0);
    }
}

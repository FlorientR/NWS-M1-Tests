<?php

namespace App\Tests\Integration;

use App\Entity\ShippingRate;
use App\Repository\ShippingRateRepository;
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
        self::assertSame(5.00, $rates[0]->getPrice());
    }

    public function testLastRateHasNullMaxWeight(): void
    {
        $rates = $this->repository->findAllOrderedByWeight();
        self::assertNull(end($rates)->getMaxWeightKg());
    }
}

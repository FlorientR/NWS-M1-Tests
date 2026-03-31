<?php

namespace App\Repository;

use App\Entity\ShippingRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShippingRate>
 */
class ShippingRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingRate::class);
    }

    /**
     * Retourne les tranches tarifaires triées par maxWeightKg ASC (NULL en dernier).
     *
     * @return ShippingRate[]
     */
    public function findAllOrderedByWeight(): array
    {
        return $this
            ->createQueryBuilder('r')
            ->orderBy('r.maxWeightKg', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

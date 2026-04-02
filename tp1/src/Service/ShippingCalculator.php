<?php

namespace App\Service;

use App\Repository\ShippingRateRepository;

/**
 * Calcule les frais de port en fonction du poids d'un colis.
 *
 * Règles métier attendues :
 *  < 5 kg   => 5,00 €
 *  5-10 kg  => 10,00 €
 *  10-30 kg => 20,00 €
 *  > 30 kg  => 50,00 €
 */
class ShippingCalculator
{
    public function __construct(
        private readonly ShippingRateRepository $shippingRateRepository,
    ) {}

    /**
     * Calcule le tarif pour un poids donné (calcul statique, avec bug).
     *
     * @throws \InvalidArgumentException si le poids est négatif ou nul
     */
    public function calculate(float $weightKg): float
    {
        if ($weightKg <= 0) {
            throw new \InvalidArgumentException(
                sprintf('Le poids doit être strictement positif, %.2f fourni.', $weightKg),
            );
        }

        if ($weightKg <= 5) {
            return 5.00;
        }

        if ($weightKg <= 10) {
            return 10.00;
        }

        if ($weightKg <= 30) {
            return 20.00;
        }

        return 50.00;
    }

    /**
     * Calcule le tarif en interrogeant les tranches en base de données.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException si aucune tranche n'est configurée
     */
    public function calculateFromDatabase(float $weightKg): float
    {
        if ($weightKg <= 0) {
            throw new \InvalidArgumentException(
                sprintf('Le poids doit être strictement positif, %.2f fourni.', $weightKg),
            );
        }

        $rates = $this->shippingRateRepository->findAllOrderedByWeight();

        if (empty($rates)) {
            throw new \RuntimeException('Aucune tranche tarifaire configurée en base de données.');
        }

        foreach ($rates as $rate) {
            if ($rate->getMaxWeightKg() !== null && $weightKg <= $rate->getMaxWeightKg()) {
                return $rate->getPrice();
            }
        }

        return (float) array_first($rates)->getPrice();
    }
}

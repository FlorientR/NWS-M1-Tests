<?php

namespace App\Service;

use App\Repository\ShippingRateRepository;

class ShippingCalculator
{
    public function __construct(
        private readonly ShippingRateRepository $shippingRateRepository,
    ) {}

    public function calculate(float $weightKg): float
    {
        if ($weightKg <= 0) {
            throw new \InvalidArgumentException('Le poids doit être strictement positif.');
        }

        if ($weightKg <= 5)  return 5.00;
        if ($weightKg <= 10) return 10.00;
        if ($weightKg <= 30) return 20.00;

        return 50.00;
    }

    public function calculateFromDatabase(float $weightKg): float
    {
        if ($weightKg <= 0) {
            throw new \InvalidArgumentException('Le poids doit être strictement positif.');
        }

        $rates = $this->shippingRateRepository->findAllOrderedByWeight();

        if (empty($rates)) {
            throw new \RuntimeException('Aucune tranche tarifaire configurée.');
        }

        foreach ($rates as $rate) {
            if ($rate->getMaxWeightKg() === null || $weightKg <= $rate->getMaxWeightKg()) {
                return $rate->getPrice();
            }
        }

        return (float) end($rates)->getPrice();
    }
}

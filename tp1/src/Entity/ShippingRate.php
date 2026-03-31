<?php

namespace App\Entity;

use App\Repository\ShippingRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingRateRepository::class)]
#[ORM\Table(name: 'shipping_rate')]
class ShippingRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Poids maximum (en kg) pour lequel ce tarif s'applique.
     * NULL signifie "illimité" (tranche haute).
     */
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $maxWeightKg;

    /** Tarif en euros */
    #[ORM\Column(type: 'float')]
    private float $price;

    /** Libellé lisible */
    #[ORM\Column(type: 'string', length: 100)]
    private string $label;

    public function __construct(float $price, string $label, ?float $maxWeightKg = null)
    {
        $this->price = $price;
        $this->label = $label;
        $this->maxWeightKg = $maxWeightKg;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxWeightKg(): ?float
    {
        return $this->maxWeightKg;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}

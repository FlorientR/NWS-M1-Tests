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

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $maxWeightKg;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'string', length: 100)]
    private string $label;

    public function __construct(float $price, string $label, ?float $maxWeightKg = null)
    {
        $this->price       = $price;
        $this->label       = $label;
        $this->maxWeightKg = $maxWeightKg;
    }

    public function getId(): ?int            { return $this->id; }
    public function getMaxWeightKg(): ?float { return $this->maxWeightKg; }
    public function getPrice(): float        { return $this->price; }
    public function getLabel(): string       { return $this->label; }
}

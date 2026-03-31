<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'integer')]
    private int $loyaltyPoints = 0;

    public function __construct(string $name, string $email)
    {
        $this->name  = $name;
        $this->email = $email;
    }

    public function getId(): ?int           { return $this->id; }
    public function getName(): string       { return $this->name; }
    public function getEmail(): string      { return $this->email; }
    public function getLoyaltyPoints(): int { return $this->loyaltyPoints; }

    public function addLoyaltyPoints(int $points): void
    {
        if ($this->loyaltyPoints + $points < 0) {
            throw new \InvalidArgumentException('Les points ne peuvent pas être négatifs.');
        }
        $this->loyaltyPoints += $points;
    }
}

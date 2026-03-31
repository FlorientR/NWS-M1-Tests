<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

class LoyaltyPointsService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly ClockInterface $clock,
    ) {}

    public function earnPoints(User $user, float $amount): int
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Le montant ne peut pas être négatif.');
        }

        $points    = (int) floor($amount);
        $dayOfWeek = (int) $this->clock->now()->format('N');

        if ($dayOfWeek >= 6) {
            $points *= 2;
        }

        $user->addLoyaltyPoints($points);
        return $points;
    }

    public function earnAndPersist(User $user, float $amount): int
    {
        $points = $this->earnPoints($user, $amount);
        $this->em->flush();
        return $points;
    }
}

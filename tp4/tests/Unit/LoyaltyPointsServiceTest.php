<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\LoyaltyPointsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

/**
 * TP4 - Tests unitaires LoyaltyPoints (version complète du TP3).
 */
class LoyaltyPointsServiceTest extends TestCase
{
    private LoyaltyPointsService $service;
    private ClockInterface $clock;

    protected function setUp(): void
    {
        $em          = $this->createStub(EntityManagerInterface::class);
        $repo        = $this->createStub(UserRepository::class);
        $this->clock = $this->createMock(ClockInterface::class);
        $this->service = new LoyaltyPointsService($em, $repo, $this->clock);
    }

    public function testOneEuroEqualsOnePointOnWeekday(): void
    {
        $this->clockReturns('2024-01-08'); // lundi
        $user   = new User('Alice', 'alice@test.com');
        $points = $this->service->earnPoints($user, 10.00);
        self::assertSame(10, $points);
    }

    public function testPointsDoubledOnSaturday(): void
    {
        $this->clockReturns('2024-01-06'); // samedi
        $user   = new User('Bob', 'bob@test.com');
        $points = $this->service->earnPoints($user, 10.00);
        self::assertSame(20, $points);
    }

    public function testPointsDoubledOnSunday(): void
    {
        $this->clockReturns('2024-01-07'); // dimanche
        $user   = new User('Carol', 'carol@test.com');
        $points = $this->service->earnPoints($user, 10.00);
        self::assertSame(20, $points);
    }

    public function testNegativeAmountThrowsException(): void
    {
        $this->clockReturns('2024-01-08');
        $this->expectException(\InvalidArgumentException::class);
        $this->service->earnPoints(new User('Dave', 'dave@test.com'), -5.0);
    }

    public function testPointsRoundedDown(): void
    {
        $this->clockReturns('2024-01-08');
        $user   = new User('Eve', 'eve@test.com');
        $points = $this->service->earnPoints($user, 9.99);
        self::assertSame(9, $points);
    }

    private function clockReturns(string $date): void
    {
        $this->clock->method('now')->willReturn(new \DateTimeImmutable($date));
    }
}

<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\LoyaltyPointsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

/**
 * TP3 - Tests UNITAIRES : LoyaltyPointsService.
 *
 * Ces tests définissent le comportement AVANT que le code n'existe.
 * C'est l'essence du TDD : écrire les tests en rouge, puis les faire passer.
 *
 * ─────────────────────────────────────────────────────────────────────────
 *  PHASE RED : lancez `make test-unit` maintenant.
 *  Tous les tests doivent être ROUGES (LogicException "À implémenter").
 *
 *  PHASE GREEN : implémentez earnPoints() dans LoyaltyPointsService
 *  avec le code MINIMAL pour faire passer testOneEuroEqualsOnePoint().
 *  Re-lancez : 1 test vert, les autres restent rouges.
 *
 *  PHASE REFACTOR : implémentez la règle du weekend.
 *  Utilisez $clock->now()->format('N') pour obtenir le numéro du jour.
 *  (1=lundi … 6=samedi, 7=dimanche)
 * ─────────────────────────────────────────────────────────────────────────
 */
class LoyaltyPointsServiceTest extends TestCase
{
    private LoyaltyPointsService $service;
    private ClockInterface $clock;

    protected function setUp(): void
    {
        $em         = $this->createStub(EntityManagerInterface::class);
        $repo       = $this->createStub(UserRepository::class);
        $this->clock = $this->createMock(ClockInterface::class);

        $this->service = new LoyaltyPointsService($em, $repo, $this->clock);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PHASE GREEN — Règle de base
    // ═══════════════════════════════════════════════════════════════════════

    public function testOneEuroEqualsOnePoint(): void
    {
        // Arrange
        $this->clockReturnsWeekday();  // Simule un lundi (pas de bonus)
        $user = new User('Alice', 'alice@example.com');

        // Act
        $points = $this->service->earnPoints($user, 10.00);

        // Assert
        self::assertSame(10, $points, '10€ doivent générer 10 points');
        self::assertSame(10, $user->getLoyaltyPoints());
    }

    public function testPointsAreRoundedDown(): void
    {
        $this->clockReturnsWeekday();
        $user = new User('Bob', 'bob@example.com');

        $points = $this->service->earnPoints($user, 10.90);

        self::assertSame(10, $points, '10.90€ doivent générer 10 points (arrondi inférieur)');
    }

    public function testZeroAmountGivesZeroPoints(): void
    {
        $this->clockReturnsWeekday();
        $user = new User('Carol', 'carol@example.com');

        $points = $this->service->earnPoints($user, 0.0);

        self::assertSame(0, $points);
    }

    public function testNegativeAmountThrowsException(): void
    {
        $this->clockReturnsWeekday();
        $user = new User('Dave', 'dave@example.com');

        $this->expectException(\InvalidArgumentException::class);

        $this->service->earnPoints($user, -5.00);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PHASE REFACTOR — Bonus du weekend
    // ═══════════════════════════════════════════════════════════════════════

    public function testPointsAreDoubledOnSaturday(): void
    {
        // Arrange — On simule un samedi
        $this->clockReturns('2024-01-06'); // 6 janvier 2024 = samedi
        $user = new User('Eve', 'eve@example.com');

        // Act
        $points = $this->service->earnPoints($user, 10.00);

        // Assert
        self::assertSame(20, $points, 'Les points doivent être doublés le samedi');
    }

    public function testPointsAreDoubledOnSunday(): void
    {
        $this->clockReturns('2024-01-07'); // 7 janvier 2024 = dimanche
        $user = new User('Frank', 'frank@example.com');

        $points = $this->service->earnPoints($user, 10.00);

        self::assertSame(20, $points, 'Les points doivent être doublés le dimanche');
    }

    public function testPointsAreNotDoubledOnFriday(): void
    {
        $this->clockReturns('2024-01-05'); // 5 janvier 2024 = vendredi
        $user = new User('Grace', 'grace@example.com');

        $points = $this->service->earnPoints($user, 10.00);

        self::assertSame(10, $points, 'Les points ne doivent PAS être doublés le vendredi');
    }

    public function testUserPointsAccumulateAfterMultiplePurchases(): void
    {
        $this->clockReturnsWeekday();
        $user = new User('Henry', 'henry@example.com');

        $this->service->earnPoints($user, 20.00);
        $this->service->earnPoints($user, 30.00);

        self::assertSame(50, $user->getLoyaltyPoints(), 'Les points doivent s\'accumuler');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function clockReturnsWeekday(): void
    {
        $this->clockReturns('2024-01-08'); // lundi
    }

    private function clockReturns(string $date): void
    {
        $this->clock
            ->method('now')
            ->willReturn(new \DateTimeImmutable($date));
    }
}

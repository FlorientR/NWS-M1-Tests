<?php

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\LoyaltyPointsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * TP3 - Tests d'INTÉGRATION : persistance des points en base SQLite.
 *
 * DAMA\DoctrineTestBundle garantit que chaque test est isolé :
 * les données insérées sont annulées après chaque test (rollback).
 *
 * ─────────────────────────────────────────────────────────────────────────
 *  PRÉREQUIS : `make db-setup`
 *
 *  EXERCICE 1 : Implémentez LoyaltyPointsService::earnAndPersist()
 *               puis lancez make test-integration.
 *
 *  EXERCICE 2 : Vérifiez qu'un 2e test peut créer un User avec le même
 *               email sans conflit (grâce au rollback DAMA).
 *
 *  EXERCICE 3 : Ajoutez un test qui vérifie qu'on peut retrouver
 *               l'utilisateur via UserRepository après earnAndPersist().
 * ─────────────────────────────────────────────────────────────────────────
 */
class LoyaltyPointsPersistenceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private LoyaltyPointsService   $service;
    private UserRepository         $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em             = self::getContainer()->get(EntityManagerInterface::class);
        $this->service        = self::getContainer()->get(LoyaltyPointsService::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    public function testEarnAndPersistSavesPointsInDatabase(): void
    {
        // Arrange
        $user = new User('Alice', 'alice@example.com');
        $this->em->persist($user);
        $this->em->flush();

        // Act — implémenter earnAndPersist() dans LoyaltyPointsService
        $points = $this->service->earnAndPersist($user, 25.00);

        // Assert
        self::assertSame(25, $points);

        // Recharger depuis la BDD pour vérifier la persistance réelle
        $this->em->clear();
        $reloaded = $this->userRepository->find($user->getId());
        self::assertNotNull($reloaded);
        self::assertSame(25, $reloaded->getLoyaltyPoints());
    }

    public function testMultipleEarnAndPersistAccumulates(): void
    {
        $user = new User('Bob', 'bob@example.com');
        $this->em->persist($user);
        $this->em->flush();

        $this->service->earnAndPersist($user, 10.00);
        $this->service->earnAndPersist($user, 15.00);

        $this->em->clear();
        $reloaded = $this->userRepository->find($user->getId());
        self::assertSame(25, $reloaded->getLoyaltyPoints());
    }

    // ── TODO EXERCICE 2 ──────────────────────────────────────────────────────
    // Créez un 2e test avec alice@example.com — doit fonctionner grâce au rollback

    // ── TODO EXERCICE 3 ──────────────────────────────────────────────────────
    // public function testUserCanBeFoundAfterEarnAndPersist(): void { ... }
}

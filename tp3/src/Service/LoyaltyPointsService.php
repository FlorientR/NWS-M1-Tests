<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * Service de gestion des points de fidélité.
 *
 * POINT DE DÉPART DU TP3 — Ce service est intentionnellement incomplet.
 * Vous allez l'implémenter étape par étape en suivant le cycle TDD.
 *
 * ==========================================================================
 * CYCLE TDD
 * ==========================================================================
 *
 * PHASE RED (tests/Unit/LoyaltyPointsServiceTest.php)
 * ─────────────────────────────────────────────────────
 *   1. Ouvrez LoyaltyPointsServiceTest.php
 *   2. Lancez : make test-unit
 *   3. Observez les tests ROUGES — les méthodes n'existent pas encore.
 *
 * PHASE GREEN
 * ─────────────────────────────────────────────────────
 *   4. Implémentez earnPoints() ci-dessous avec le code MINIMAL
 *      pour faire passer le premier test (1€ = 1 point).
 *   5. Relancez : make test-unit => 1 test VERT
 *
 * PHASE REFACTOR
 * ─────────────────────────────────────────────────────
 *   6. Implémentez la règle du weekend (points doublés).
 *      Injectez ClockInterface pour contrôler la date dans les tests.
 *   7. Vérifiez que TOUS les tests restent verts.
 *
 * PERSISTANCE (tests/Integration/)
 * ─────────────────────────────────────────────────────
 *   8. Implémentez earnAndPersist().
 *   9. Lancez : make test-integration
 */
class LoyaltyPointsService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly ClockInterface $clock,
    ) {}

    /**
     * Calcule et attribue les points de fidélité pour un montant dépensé.
     *
     * Règles :
     *  - 1€ dépensé = 1 point gagné
     *  - Le week-end (samedi et dimanche) : les points sont DOUBLÉS
     *
     * TODO PHASE GREEN  : implémentez la règle de base (1€ = 1 point)
     * TODO PHASE REFACTOR : ajoutez la règle du weekend avec $this->clock
     *
     * @param User  $user      L'utilisateur qui reçoit les points
     * @param float $amount    Montant de l'achat en euros
     * @return int             Nombre de points attribués
     * @throws \InvalidArgumentException si le montant est négatif
     */
    public function earnPoints(User $user, float $amount): int
    {
        $points = (int) $amount;
        $day = (int) $this->clock->now()->format('N');
        if ($day === 6 || $day === 7) {
            $points *= 2;
        }

        $userPoints = $user->getLoyaltyPoints();
        $newUserPoints = $userPoints + $points;
        $user->setLoyaltyPoints($newUserPoints);

        return $newUserPoints;
    }

    /**
     * Attribue les points ET les persiste en base de données.
     *
     * TODO PERSISTANCE : appelez earnPoints() puis persistez via EntityManager
     *
     * @return int Nombre de points attribués
     */
    public function earnAndPersist(User $user, float $amount): int
    {
        $this->earnPoints($user, $amount);
        $this->em->persist($user);
        $this->em->flush();

        return $user->getLoyaltyPoints();
    }
}

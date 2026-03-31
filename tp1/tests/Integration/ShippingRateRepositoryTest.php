<?php

namespace App\Tests\Integration;

use App\Entity\ShippingRate;
use App\Repository\ShippingRateRepository;
use App\Service\ShippingCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests D'INTÉGRATION du repository ShippingRateRepository et de
 * ShippingCalculator::calculateFromDatabase().
 *
 * Ces tests démarrent le kernel Symfony et écrivent en base de données réelle
 * (SQLite de test). Chaque test s'exécute dans une transaction annulée en
 * tearDown() pour garantir l'isolation entre les cas.
 *
 * Prérequis : la base de données de test doit exister et les migrations
 * doivent avoir été appliquées (`make install` ou `make test`).
 */
class ShippingRateRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ShippingRateRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->em = self::getContainer()->get('doctrine')->getManager();
        $this->repository = self::getContainer()->get(ShippingRateRepository::class);

        // Démarre une transaction pour isoler ce test des autres.
        // tearDown() la rollback automatiquement → aucune donnée persistée.
        $this->em->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Annule toutes les écritures effectuées pendant le test.
        $this->em->rollback();

        parent::tearDown();
    }

    // -------------------------------------------------------------------------
    // Méthodes utilitaires privées
    // -------------------------------------------------------------------------

    /**
     * Crée et persiste une tranche tarifaire en base.
     */
    private function createRate(float $price, string $label, ?float $maxWeightKg): ShippingRate
    {
        $rate = new ShippingRate($price, $label, $maxWeightKg);
        $this->em->persist($rate);
        $this->em->flush();

        return $rate;
    }

    // -------------------------------------------------------------------------
    // Exemples implémentés
    // -------------------------------------------------------------------------

    /**
     * Sans aucune tranche en base, le repository doit retourner un tableau vide.
     */
    public function testFindAllOrderedByWeightReturnsEmptyArrayWhenNoRates(): void
    {
        // Arrange — base vide garantie par la transaction de setUp()

        // Act
        $rates = $this->repository->findAllOrderedByWeight();

        // Assert
        $this->assertIsArray($rates);
        $this->assertEmpty($rates);
    }

    /**
     * Les tranches doivent être triées par maxWeightKg ASC, avec NULL en dernier
     * (la tranche "illimitée" doit toujours être la dernière).
     *
     * On insère volontairement dans le désordre pour valider le tri.
     */
    public function testFindAllOrderedByWeightReturnRatesInAscendingOrder(): void
    {
        // Arrange — insertion dans le désordre
        $this->createRate(50.00, 'Très lourd (illimité)', null);   // NULL = illimité
        $this->createRate(10.00, 'Moyen (≤ 10 kg)', 10.0);
        $this->createRate(5.00,  'Léger (≤ 5 kg)',   5.0);
        $this->createRate(20.00, 'Lourd (≤ 30 kg)',  30.0);

        // Act
        $rates = $this->repository->findAllOrderedByWeight();

        // Assert
        $this->assertCount(4, $rates);
        $this->assertSame(5.0,  $rates[0]->getMaxWeightKg());
        $this->assertSame(10.0, $rates[1]->getMaxWeightKg());
        $this->assertSame(30.0, $rates[2]->getMaxWeightKg());
        $this->assertNull($rates[3]->getMaxWeightKg()); // tranche haute (illimitée)
    }

    // -------------------------------------------------------------------------
    // Méthodes à compléter
    // -------------------------------------------------------------------------

    /**
     * Insérer une seule tranche et vérifier que le repository en retourne bien une.
     * S'assurer que les champs price, label et maxWeightKg sont correctement lus.
     */
    public function testFindAllOrderedByWeightReturnsSingleRate(): void
    {
        // TODO : créer une tranche, appeler findAllOrderedByWeight(),
        //        asserter count = 1 et vérifier les valeurs des getters
        self::markTestSkipped();
    }

    /**
     * calculateFromDatabase() doit lever une RuntimeException quand aucune tranche
     * n'est configurée en base.
     */
    public function testCalculateFromDatabaseThrowsExceptionWhenNoRatesConfigured(): void
    {
        // TODO : récupérer ShippingCalculator via le container,
        //        appeler calculateFromDatabase(5.0) sans aucune tranche en base
        //        et asserter RuntimeException avec message "Aucune tranche tarifaire"
        self::markTestSkipped();
    }

    /**
     * calculateFromDatabase() doit retourner le bon tarif en fonction du poids
     * pour les tranches standard (< 5 kg, 5-10 kg, 10-30 kg, illimité).
     */
    public function testCalculateFromDatabaseReturnsCorrectRateForEachWeightBand(): void
    {
        // TODO : insérer les 4 tranches standard, puis appeler calculateFromDatabase()
        //        avec un poids dans chaque tranche et vérifier le tarif retourné
        self::markTestSkipped();
    }

    /**
     * Quand le poids dépasse toutes les tranches à maxWeightKg non-null,
     * calculateFromDatabase() doit utiliser la tranche NULL (illimitée).
     */
    public function testCalculateFromDatabaseFallsBackToUnlimitedBand(): void
    {
        // TODO : insérer des tranches dont la plus haute a maxWeightKg = 30.0,
        //        puis appeler calculateFromDatabase(50.0) et vérifier le tarif illimité
        self::markTestSkipped();
    }

    /**
     * calculateFromDatabase() avec un poids négatif doit lever une InvalidArgumentException.
     */
    public function testCalculateFromDatabaseThrowsExceptionForNonPositiveWeight(): void
    {
        // TODO : appeler calculateFromDatabase(-1.0) et asserter InvalidArgumentException
        self::markTestSkipped();
    }
}

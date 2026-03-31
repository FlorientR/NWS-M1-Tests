<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * TP4 - Tests applicatifs CartController (version corrigée du TP1).
 *
 * EXERCICE TP4 — Couverture de code :
 *   Lancez `make coverage` puis ouvrez var/coverage-report/index.html.
 *   Identifiez les branches non couvertes et ajoutez des tests.
 */
class CartControllerTest extends WebTestCase
{
    public function testCartPageReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart');
        self::assertResponseIsSuccessful();
    }

    public function testCartPageContainsShippingCostLabel(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('p', 'Frais de port');
    }

    public function testAddToCartReturns200(): void
    {
        $client = static::createClient();
        $client->request('POST', '/cart/add', ['product_name' => 'Test', 'weight' => '2.5']);
        self::assertResponseIsSuccessful();
    }

    public function testCartAddWithGetReturns405(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/add');
        self::assertResponseStatusCodeSame(405);
    }

    // ── TODO EXERCICE — Couverture ───────────────────────────────────────────
    // Ajoutez un test pour un poids > 30 kg (tranche à 50 €)
    // et vérifiez que le contenu HTML reflète le bon montant.
}

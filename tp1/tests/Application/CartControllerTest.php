<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests APPLICATIFS du CartController.
 *
 * Ces tests envoient de vraies requêtes HTTP via le client Symfony et vérifient
 * les réponses (code HTTP, contenu HTML). Ils couvrent l'intégration complète :
 * routing → controller → service → template.
 */
class CartControllerTest extends WebTestCase
{
    // -------------------------------------------------------------------------
    // Exemples implémentés
    // -------------------------------------------------------------------------

    /**
     * La route POST /cart/add doit répondre 200 et afficher le nom du produit.
     */
    public function testAddToCartReturnsSuccessfulResponseWithProductName(): void
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('POST', '/cart/add', [
            'product_name' => 'Clavier mécanique',
            'weight'       => '1.2',
        ]);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Clavier mécanique');
    }

    /**
     * La route GET /cart doit retourner une réponse 200.
     */
    public function testCartPageReturnsSuccessfulResponse(): void
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/cart');

        // Assert
        $this->assertResponseIsSuccessful();
    }

    // -------------------------------------------------------------------------
    // Méthodes à compléter
    // -------------------------------------------------------------------------

    /**
     * La route GET /cart doit afficher les articles du panier (nom, prix, poids).
     * Vérifier que les deux articles définis dans le controller apparaissent dans le HTML.
     */
    public function testCartPageDisplaysItems(): void
    {
        // TODO : créer un client, faire GET /cart,
        //        asserter que "Livre PHP 8" et "Clavier mécanique" sont présents
        self::markTestSkipped();
    }

    /**
     * La page /cart doit afficher le montant des frais de port.
     */
    public function testCartPageDisplaysShippingCost(): void
    {
        // TODO : faire GET /cart et asserter que le texte "Frais de port"
        //        suivi d'un montant en euros est présent dans le HTML
        self::markTestSkipped();
    }

    /**
     * La route /cart/add ne doit accepter que la méthode POST.
     * Une requête GET doit retourner un code 405 (Method Not Allowed).
     */
    public function testAddToCartRequiresPostMethod(): void
    {
        // TODO : faire GET /cart/add et asserter assertResponseStatusCodeSame(405)
        self::markTestSkipped();
    }

    /**
     * Quand le paramètre product_name est absent du POST, le controller utilise
     * la valeur par défaut "Article inconnu". Vérifier que cette valeur apparaît.
     */
    public function testAddToCartUsesDefaultProductNameWhenMissing(): void
    {
        // TODO : faire POST /cart/add SANS product_name,
        //        asserter que "Article inconnu" apparaît dans la réponse
        self::markTestSkipped();
    }

    /**
     * Les frais de port affichés sur /cart/add doivent correspondre au tarif
     * attendu pour le poids fourni (cf. règles métier de ShippingCalculator).
     */
    public function testAddToCartDisplaysCorrectShippingCost(): void
    {
        // TODO : envoyer weight=1.0 et asserter que le montant affiché est correct
        self::markTestSkipped();
    }
}

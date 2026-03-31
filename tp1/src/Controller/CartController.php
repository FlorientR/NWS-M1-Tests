<?php

namespace App\Controller;

use App\Service\ShippingCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(
        private readonly ShippingCalculator $shippingCalculator,
    ) {}

    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(): Response
    {
        $items = [
            ['name' => 'Livre PHP 8', 'price' => 29.99, 'weight' => 0.5],
            ['name' => 'Clavier mécanique', 'price' => 89.99, 'weight' => 1.2],
        ];

        $totalWeight = array_sum(array_column($items, 'weight'));
        $shippingCost = $this->shippingCalculator->calculate($totalWeight);

        return $this->render('cart/index.html.twig', [
            'items' => $items,
            'shipping_cost' => $shippingCost,
            'totalWeight' => $totalWeight,
        ]);
    }

    #[Route('/cart/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $productName = $request->request->get('product_name', 'Article inconnu');
        $weight = (float) $request->request->get('weight', 1.0);
        $shippingCost = $this->shippingCalculator->calculate($weight);

        return $this->render('cart/add.html.twig', [
            'product' => $productName,
            'shippingCost' => $shippingCost,
        ]);
    }
}

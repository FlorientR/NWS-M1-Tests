<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    public function __construct(private readonly WeatherService $weatherService)
    {

    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        if ($request->request->has('city')) {
            $city = $request->request->get('city');

            try {
                $condition = $this->weatherService->getCurrentCondition($city);
                $message = 'La météo actuelle pour ' . $city . ' est : ' . $condition . '.';
            } catch (ClientException $e) {
                if ($e->getResponse()->getStatusCode() === 404) {
                    $message = 'Ville inconnue';
                } else {
                    $message = 'Une erreur est survenue.';
                }
            } catch (\Exception $e) {
                $message = 'Une erreur est survenue.';
            }

            return $this->render('index.html.twig', [
                'message' => $message,
            ]);
        }

        return $this->render('index.html.twig');
    }
}

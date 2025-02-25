<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dash', name: 'app_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user_id' => $user ? $user->getId() : null, // Récupère l'ID du user connecté
        ]);
    }
}

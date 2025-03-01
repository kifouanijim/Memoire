<?php

// App\Controller\AdminController.php

namespace App\Controller;

use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/statistiques', name: 'admin_statistiques')]
    public function stats(ReviewsRepository $reviewsRepository): Response
    {
        // Récupérer les statistiques des critiques par jour
        $reviewsData = $reviewsRepository->getReviewsCountByDay();

        // Formater les données pour le graphique
        $dates = array_column($reviewsData, 'day');
        $counts = array_column($reviewsData, 'review_count');

        return $this->render('stats/stats.html.twig', [
            'dates' => json_encode($dates),
            'counts' => json_encode($counts),
        ]);
    }
}



<?php

// App\Controller\AdminController.php

namespace App\Controller;

use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SalesRepository;


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
        
        // Extraire les commentaires groupés par utilisateur et par date
        $commentsByUser = [];
        foreach ($reviewsData as $review) {
            // Groupement des commentaires par utilisateur pour chaque jour
            foreach ($review['comments_by_user'] as $user => $comments) {
                $commentsByUser[$review['day']][$user] = $comments;
            }
        }
        
        // Retourner les données au template
        return $this->render('stats/stats.html.twig', [
            'dates' => json_encode($dates),
            'counts' => json_encode($counts),
            'commentsByUser' => json_encode($commentsByUser), // Passer les commentaires groupés par utilisateur
        ]);
    }
    
    
    #[Route('/admin/sales-stats', name: 'admin_sales_stats')]
    public function salesStats(SalesRepository $salesRepository): Response
    {
        $salesByDay = $salesRepository->getSalesCountByDay();
        $salesByMonth = $salesRepository->getSalesCountByMonth();
    
        return $this->render('stats/sales_stats.html.twig', [
            'salesByDay' => json_encode($salesByDay, JSON_UNESCAPED_UNICODE),
            'salesByMonth' => json_encode($salesByMonth, JSON_UNESCAPED_UNICODE),
        ]);
    }
    


}



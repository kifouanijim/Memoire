<?php

// App\Controller\AdminController.php

namespace App\Controller;

use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SalesRepository;

#[Route('/admin')] // Préfixe pour toutes les routes
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')] // Route pour le tableau de bord
    public function index(ReviewsRepository $reviewsRepository, SalesRepository $salesRepository): Response
    {
        // Récupérer les statistiques des critiques par jour
        $reviewsData = $reviewsRepository->getReviewsCountByDay();
        $dates = array_column($reviewsData, 'day');
        $counts = array_column($reviewsData, 'review_count');
        $commentsByUser = [];
        foreach ($reviewsData as $review) {
            foreach ($review['comments_by_user'] as $user => $comments) {
                $commentsByUser[$review['day']][$user] = $comments;
            }
        }

        // Récupérer les statistiques des ventes par jour et par mois
        $salesByDay = $salesRepository->getSalesCountByDay();
        $salesByMonth = $salesRepository->getSalesCountByMonth();

        return $this->render('admin/index.html.twig', [
            'reviewsByDay' => json_encode(array_map(fn($review) => $review['review_count'], $reviewsData)),
            'commentsByUser' => json_encode($commentsByUser),
            'dates' => json_encode($dates),
            'salesByDay' => json_encode($salesByDay, JSON_UNESCAPED_UNICODE),
            'salesByMonth' => json_encode($salesByMonth, JSON_UNESCAPED_UNICODE),
        ]);
    }
}

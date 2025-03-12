<?php

namespace App\Controller;

use App\Repository\ReviewsRepository;
use App\Repository\SalesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(ReviewsRepository $reviewsRepository, SalesRepository $salesRepository): Response
    {
        // Récupération des statistiques des reviews par jour
        $reviewsData = $reviewsRepository->getReviewsCountByDay();
        $dates = array_column($reviewsData, 'day');

        // Récupération des statistiques des ventes
        $salesByDay = $salesRepository->getSalesCountByDay();
        $salesByMonth = $salesRepository->getSalesCountByMonth();

        // Séparer les données de ventes et de téléphones vendus
        $salesCountByDay = array_map(fn($sale) => [
            'day' => $sale['day'],
            'sales_count' => $sale['sales_count'],
        ], $salesByDay);

        $phonesSoldByDay = array_map(fn($sale) => [
            'day' => $sale['day'],
            'total_phones_sold' => $sale['total_phones_sold'],
        ], $salesByDay);

        return $this->render('admin/index.html.twig', [
            'reviewsByDay' => json_encode(array_map(fn($review) => $review['review_count'], $reviewsData)),
            'dates' => json_encode($dates),
            'salesCountByDay' => json_encode($salesCountByDay, JSON_UNESCAPED_UNICODE),
            'phonesSoldByDay' => json_encode($phonesSoldByDay, JSON_UNESCAPED_UNICODE),
            'salesByMonth' => json_encode($salesByMonth, JSON_UNESCAPED_UNICODE),
        ]);
    }
}

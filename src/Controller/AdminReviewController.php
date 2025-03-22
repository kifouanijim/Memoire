<?php

namespace App\Controller;

use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminReviewController extends AbstractController
{
    #[Route('/reviews', name: 'admin_reviews')]
    public function index(ReviewsRepository $reviewsRepository)
    {
        // Récupérer toutes les critiques
        $reviews = $reviewsRepository->findAll();
        
        // Initialiser les compteurs pour les sentiments
        $sentiments = [
            'normal' => 0,
            'urgent' => 0,
            'prioritaire' => 0
        ];

        // Compter le nombre de chaque sentiment
        foreach ($reviews as $review) {
            $sentiment = $review->getSentiment(); // Si 'getSentiment()' retourne un tableau, nous devons le gérer correctement
            if (is_array($sentiment)) {
                // Si le sentiment est un tableau, on l'itère pour compter chaque sentiment
                foreach ($sentiment as $sent) {
                    if (isset($sentiments[$sent])) {
                        $sentiments[$sent]++;
                    }
                }
            } else {
                // Si c'est une simple chaîne, on l'ajoute directement
                if (isset($sentiments[$sentiment])) {
                    $sentiments[$sentiment]++;
                }
            }
        }

        // Passer les critiques et les données des sentiments à la vue
        return $this->render('admin_review/index.html.twig', [
            'reviews' => $reviews,
            'sentiments' => $sentiments
        ]);
    }
}

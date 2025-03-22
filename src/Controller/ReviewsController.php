<?php

namespace App\Controller;

use App\Entity\Reviews;
use App\Form\Reviews1Type;
use App\Repository\ReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reviews')]
final class ReviewsController extends AbstractController
{
    #[Route(name: 'app_reviews_index', methods: ['GET'])]
    public function index(ReviewsRepository $reviewsRepository): Response
    {
        $user = $this->getUser();
        return $this->render('reviews/index.html.twig', [
            'reviews' => $reviewsRepository->findAll(),
            'user_id' => $user ? $user->getId() : null,
        ]);
    }

    #[Route('/new', name: 'app_reviews_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new Reviews();
        $form = $this->createForm(Reviews1Type::class, $review);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if ($user) {
                $review->setUser($user);
            }
    
            // Récupération de la valeur du sentiment (c'est une chaîne, pas un tableau)
            $sentiment = $form->get('sentiment')->getData();
    
            // Pas besoin de manipuler ou de convertir sentiment en tableau, c'est une chaîne
            $review->setSentiment($sentiment);
    
            $entityManager->persist($review);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reviews_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reviews/new.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_reviews_show', methods: ['GET'])]
    public function show(Reviews $review): Response
    {
        return $this->render('reviews/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reviews_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reviews $review, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reviews1Type::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sentiment = $form->get('sentiment')->getData();
            if (!is_array($sentiment)) {
                $sentiment = explode(',', $sentiment);
            }
            $review->setSentiment(array_map('trim', $sentiment));
            
            $entityManager->flush();

            return $this->redirectToRoute('app_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reviews/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reviews_delete', methods: ['POST'])]
    public function delete(Request $request, Reviews $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reviews_index', [], Response::HTTP_SEE_OTHER);
    }
}

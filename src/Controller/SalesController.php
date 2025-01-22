<?php

namespace App\Controller;

use App\Entity\Sales;
use App\Form\SalesType;
use App\Repository\SalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sales')]
final class SalesController extends AbstractController{
    #[Route(name: 'app_sales_index', methods: ['GET'])]
    public function index(SalesRepository $salesRepository): Response
    {
        return $this->render('sales/index.html.twig', [
            'sales' => $salesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sales_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sale = new Sales();
        $form = $this->createForm(SalesType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sale);
            $entityManager->flush();

            return $this->redirectToRoute('app_sales_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sales/new.html.twig', [
            'sale' => $sale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sales_show', methods: ['GET'])]
    public function show(Sales $sale): Response
    {
        return $this->render('sales/show.html.twig', [
            'sale' => $sale,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sales_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sales $sale, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalesType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sales_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sales/edit.html.twig', [
            'sale' => $sale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sales_delete', methods: ['POST'])]
    public function delete(Request $request, Sales $sale, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sale->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sale);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sales_index', [], Response::HTTP_SEE_OTHER);
    }
}

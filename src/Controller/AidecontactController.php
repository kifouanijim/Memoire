<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AidecontactController extends AbstractController
{
    #[Route('/aidecontact', name: 'app_aidecontact')]
    public function index(): Response
    {
        return $this->render('aidecontact/index.html.twig', [
            'controller_name' => 'AidecontactController',
        ]);
    }
}

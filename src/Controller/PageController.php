<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    /**
     * Method to display the legales mentions page
     */
    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig');
    }
}

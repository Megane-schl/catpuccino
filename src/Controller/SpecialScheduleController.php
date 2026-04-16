<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SpecialScheduleController extends AbstractController
{
    #[Route('/special/schedule', name: 'app_special_schedule')]
    public function index(): Response
    {
        return $this->render('special_schedule/index.html.twig', [
            'controller_name' => 'SpecialScheduleController',
        ]);
    }
}

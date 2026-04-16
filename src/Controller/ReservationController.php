<?php

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation', name: 'app_reservation_')]
final class ReservationController extends AbstractController
{
    /**
     * 
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {

        //collect the selected date in th correct format of the database
        $strSelectedDate = $request->query->get('date', date('Y-m-d'));

        //convert it into an object
        $objSelectedDate = new DateTimeImmutable($strSelectedDate);

        return $this->render('reservation/index.html.twig', [
            'selectedDate'  => $objSelectedDate->format('Y-m-d'),
            'timeSlots'     => [],
        ]);
    }
}

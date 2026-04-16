<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleFormType;
use App\Repository\ScheduleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/schedule', name: 'app_schedule_')]
final class ScheduleController extends AbstractController
{
    /**
     * Method to display all the week days
     * @param ScheduleRepository $scheduleRepository To collect the week days
     * @return Response The list of the days
     */
    #[Route('/', name: 'index')]
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        $arrSchedules = $scheduleRepository->findAllDayOrder();
        return $this->render('schedule/index.html.twig', [
            'scheduleList' => $arrSchedules,
        ]);
    }

    /**
     * Method to update a schedule day in the database
     * @param Request $request To collect the news informations about the day
     * @param EntityManagerInterface $entityManager Use to update the day
     * @param Schedule $schedule The day to update
     * @return Response The success or the failure of updating the day and redirect to the schedule list
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    public function update(Schedule $schedule, Request $request, EntityManagerInterface $entityManager): Response
    {

        $updateForm = $this->createForm(ScheduleFormType::class, $schedule);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {

            $schedule->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            // ->value or getDay return an object and not a string
            $this->addFlash('success', $schedule->getDay()->value . " a été mis à jour");

            return $this->redirectToRoute('app_schedule_index');
        }

        return $this->render('schedule/update.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier un jour',
            'subtitle'      => 'Édition de : ' . $schedule->getDay()->value
        ]);
    }
}

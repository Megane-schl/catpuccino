<?php

namespace App\Controller;

use App\Entity\SpecialSchedule;
use App\Form\SpecialScheduleFormType;
use App\Repository\SpecialScheduleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/schedule/special', name: 'app_special_schedule_')]
#[IsGranted('ROLE_ADMIN')]
final class SpecialScheduleController extends AbstractController
{
    /**
     * Method to display special schedule
     * @param SpecialScheduleRepository $specialScheduleRepository To collect the specials schedules
     * @return Response The list of specials schedules
     */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(SpecialScheduleRepository $specialScheduleRepository): Response
    {
        $arrSpecialsSchedules = $specialScheduleRepository->findAllActive();
        return $this->render('special_schedule/index.html.twig', [
            'specialScheduleList' => $arrSpecialsSchedules,
        ]);
    }

    /**
     * Method to create a new special schedule in the database
     * @param Request $request To collect the new special schedule
     * @param EntityManagerInterface $entityManager Use to create the new special schedule
     * @return Response The success or the failure of creating a special schedule and redirect to the special schedule list
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objSpecialSchedule = new SpecialSchedule();

        $createForm = $this->createForm(SpecialScheduleFormType::class, $objSpecialSchedule);

        $createForm->handleRequest($request);

        
        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $objSpecialSchedule->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objSpecialSchedule);
            $entityManager->flush();

            $this->addFlash('success', "L'horaire exceptionnel a été ajouté");

            return $this->redirectToRoute('app_special_schedule_index');
        }


        return $this->render('special_schedule/form.html.twig', [
            'createForm'    => $createForm,
            'title'         => 'Ajouter un horaire',
            'subtitle'      => 'Un horaire pour une occasion spéciale'
        ]);
    }

    /**
     * Method to update an exceptional day in the database
     * @param Request $request To collect the news informations about exceptional day
     * @param EntityManagerInterface $entityManager Use to update the exceptional day
     * @param SpecialSchedule $specialSchedule The exceptional day to update
     * @return Response The success or the failure of updating the exceptional day and redirect to the exception schedule list
     */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('ROLE_ADMIN')]
    public function update(SpecialSchedule $specialSchedule, Request $request, EntityManagerInterface $entityManager): Response
    {

        $updateForm = $this->createForm(SpecialScheduleFormType::class, $specialSchedule);

        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {

            $specialSchedule->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->flush();

            // ->value or getDay return an object and not a string
            $this->addFlash('success', 'Le jour d\'exception prévu le '  . $specialSchedule->getName() . ' à été mis à jour');

            return $this->redirectToRoute('app_special_schedule_index');
        }

        return $this->render('special_schedule/form.html.twig', [
            'createForm'    => $updateForm,
            'title'         => 'Modifier un jour d\'exception',
            'subtitle'      => 'Édition de : ' . $specialSchedule->getName()
        ]);
    }

    /**
     * Method to soft delete a special schedule
     * @param SpecialSchedule $specialSchedule The special schedule to delete
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of deleting the special schedule and redirect to the special schedule list
     */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsCsrfTokenValid('delete-special-schedule', '_csrf_token')]
    public function delete(SpecialSchedule $specialSchedule, EntityManagerInterface $entityManager): Response
    {
        try {
            $specialSchedule->setDeletedAt(new DateTimeImmutable('now'));
            $entityManager->flush();
            $this->addFlash('success', "L'horaire exceptionnel a été supprimé");
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        return $this->redirectToRoute('app_special_schedule_index');
    }
}

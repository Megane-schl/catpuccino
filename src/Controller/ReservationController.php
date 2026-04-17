<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\ScheduleRepository;
use App\Repository\SpecialScheduleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation', name: 'app_reservation_')]
final class ReservationController extends AbstractController
{
    /**
     * 
     */
    #[Route('/', name: 'index')]
    public function index(
        Request $request
    ): Response {


        return $this->render('reservation/index.html.twig');
    }

    /**
     * Method to generate available time slots for a date 
     * Verify if there is a special schedule on this date or if the coffee is closed
     * Calculate how much places stay on a time slot
     * @param DateTimeImmutable $objDate The Selected date
     * @param ScheduleRepository $scheduleRepository To find the regular weekly schedule
     * @param SpecialScheduleRepository $specialScheduleRepository To find exceptionnal schedule
     * @param ReservationRepository $reservationRepository To count existing reservation per slot
     * @return array The list of time slot availables
     */
    private function generateTimeSlots(
        DateTimeImmutable $objDate,
        ScheduleRepository $scheduleRepository,
        SpecialScheduleRepository $specialScheduleRepository,
        ReservationRepository $reservationRepository
    ): array {

        //Search for the schedule of the day
        $specialSchedule = $specialScheduleRepository->findOneByDate($objDate);

        //search if there is a special schedule at this date
        if ($specialSchedule !== null) {
            //and if this special schedule is close -> return empty array
            if ($specialSchedule->isClosed()) {
                return [];
            }

            $objOpentime    = $specialSchedule->getOpenTime();
            $objCloseTime   = $specialSchedule->getCloseTime();
            $objMaxPeople   = $specialSchedule->getMaxPeople();
        }
        // else if there is no special schedule at this day we search for the regular week day in the schedule
        else {
            //
            $schedule = $scheduleRepository->find($objDate->format('N')); //<-- N : mean 1 for Monday, 7 For Sunday...

            // if the regular schedule is close return empty array
            if ($schedule->isClose()) {
                return [];
            }
            // collecting the open time / close time and max people 
            $objOpentime    = $schedule->getOpenTime();
            $objCloseTime   = $schedule->getCloseTime();
            $objMaxPeople   = $schedule->getMaxPeople();
        }

        $arrTimeSlots = [];

        /*
        h -> hours
        i -> minutes
        */
        $objSlotStart   = $objDate->setTime($objOpentime->format('H'), $objOpentime->format('i'));
        $objDayEnd      = $objDate->setTime($objCloseTime->format('H'), $objCloseTime->format('i'));
        $objSlotEnd     = '';


        //collect the current datetime
        $objNow     = new DateTimeImmutable();

        while ($objSlotStart < $objDayEnd) {

            $objSlotEnd = $objSlotStart->modify('+ 60 minutes');

            // if the schedule is bigger than the close time we stop the loop
            if ($objSlotEnd > $objDayEnd) {
                break;
            }

            // collect how people has already reserved
            $intReserved    = $reservationRepository->countPeopleForTimeSlot($objSlotStart);
            // boolean to know if the time slot is full or not
            $blIsFull       = ($intReserved >= $objMaxPeople);
            // to not reserved in the past
            $blIsPast       = ($objSlotStart < $objNow);

            /*  show every time slot with : the datetime selected, the time slot selected and the maximum people 
            to calculate how much places is available */
            $arrTimeSlots[] = [
                'timeSlot'  => $objSlotStart,
                'label'     => $objSlotStart->format('H:i') . ' - ' . $objSlotEnd->format('H:i'),
                'max'       => $objMaxPeople,
                'available' => $objMaxPeople - (int)$intReserved,
                'isFull'    => $blIsFull,
                'isPast'    => $blIsPast
            ];

            $objSlotStart = $objSlotEnd;
        }
        return $arrTimeSlots;
    }

    /**
     * Method to create a new reservarion in the database
     * @param Request $request To collect the new reservation
     * @param EntityManagerInterface $entityManager Use to create the new reservation
     * @return Response The success or the failure of creating a reservation 
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    // #[IsCsrfTokenValid('create-reservation', '_csrf_token')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ScheduleRepository $scheduleRepository,
        SpecialScheduleRepository $specialScheduleRepository,
        ReservationRepository $reservationRepository
    ): Response {

        //collect the selected date in th correct format of the database
        $strSelectedDate = $request->query->get('date', date('Y-m-d'));

        //convert it into an object
        $objSelectedDate = new DateTimeImmutable($strSelectedDate);

        $arrTimeSlots = $this->generateTimeSlots(
            $objSelectedDate,
            $scheduleRepository,
            $specialScheduleRepository,
            $reservationRepository
        );


        if ($request->isMethod('POST')) {
            $objReservation = new Reservation();

            $objReservation->setTimeSlot(new DateTimeImmutable($request->request->get('timeSlot')));
            $objReservation->setNbPeople($request->request->get('nbPeople'));
            $objReservation->setCreatedBy($this->getUser());
            $objReservation->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($objReservation);
            $entityManager->flush();

            $this->addFlash('success', "Votre réservation a été enregistrée");
            return $this->redirectToRoute('app_reservation_index');
        }


        return $this->render('reservation/create.html.twig', [
            'selectedDate' => $objSelectedDate->format('Y-m-d'),
            'timeSlots'    => $arrTimeSlots,
        ]);
    }
}

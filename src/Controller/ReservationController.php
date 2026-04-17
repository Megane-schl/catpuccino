<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\ScheduleRepository;
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

#[Route('/reservation', name: 'app_reservation_')]
final class ReservationController extends AbstractController
{
    /**
     * 
     */
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {

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
        $objNow         = new DateTimeImmutable();

        while ($objSlotStart < $objDayEnd) {

            $objSlotEnd = $objSlotStart->modify('+ 60 minutes');

            // if the schedule is bigger than the close time we stop the loop
            if ($objSlotEnd > $objDayEnd) {
                break;
            }

            // collect how much people has already reserved
            $intReserved    = $reservationRepository->countPeopleForTimeSlot($objSlotStart);
            // boolean to know if the time slot is full or not
            $blIsFull       = ($intReserved >= $objMaxPeople);
            // to not reserved in the past
            $blIsPast       = ($objSlotEnd < $objNow);

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
    // #[IsCsrfTokenValid('reservation-create', '_csrf_token')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ScheduleRepository $scheduleRepository,
        SpecialScheduleRepository $specialScheduleRepository,
        ReservationRepository $reservationRepository
    ): Response {

        //collect the selected date in the correct format of the database
        //-> generate error "please select a time slot even too i did it
        if ($request->isMethod('POST')) {
            $strSelectedDate = $request->getPayload()->get('date', date('Y-m-d'));
        } else {
            $strSelectedDate = $request->query->get('date', date('Y-m-d'));
        }

        //convert it into an object
        $objSelectedDate = new DateTimeImmutable($strSelectedDate);

        $arrTimeSlots = $this->generateTimeSlots(
            $objSelectedDate,
            $scheduleRepository,
            $specialScheduleRepository,
            $reservationRepository
        );


        if ($request->isMethod('POST')) {

            // verify token
            $submittedToken = $request->getPayload()->get('_csrf_token');

            if ($this->isCsrfTokenValid('reservation-create', $submittedToken)) {

                $strTimeSlot = $request->getPayload()->get('timeSlot');
                $intNbPeople = (int)$request->getPayload()->get('nbPeople');
                $objTimeSlot = new DateTimeImmutable($strTimeSlot);

                $arrMatchingSlot = '';
                //loop to check if the time slot selected is matching with one time slot in the loop
                foreach ($arrTimeSlots as $slot) {
                    if ($slot['timeSlot'] == $objTimeSlot) {
                        $arrMatchingSlot = $slot;
                        break;
                    }
                }
                //error message
                if ($arrMatchingSlot == null) {
                    $this->addFlash('danger', 'Veuillez sélectionnez un créneau');
                    return $this->redirectToRoute('app_reservation_create');
                }
                if ($intNbPeople < 1) {
                    $this->addFlash('danger', 'Veuillez réserver pour au moins une personne');
                    return $this->redirectToRoute('app_reservation_create');
                }
                $noDecimal = $request->getPayload()->get('nbPeople');
                if ($noDecimal != (int)$noDecimal) {
                    $this->addFlash('danger', 'Veuillez entrez un nombre entier');
                    return $this->redirectToRoute('app_reservation_create');
                }
                if ($arrMatchingSlot['isPast']) {
                    $this->addFlash('danger', 'Ce créneau est déjà passé');
                    return $this->redirectToRoute('app_reservation_create');
                }
                if ($arrMatchingSlot['isFull']) {
                    $this->addFlash('danger', 'Désolé, ce créneau est complet.');
                    return $this->redirectToRoute('app_reservation_create');
                }

                if ($intNbPeople > $arrMatchingSlot['available']) {
                    $this->addFlash('danger', 'Désolé, il n\'y a plus assez de places pour ce créneau.');
                    return $this->redirectToRoute('app_reservation_create');
                }

                $objReservation = new Reservation();
                $objReservation->setTimeSlot($objTimeSlot);
                $objReservation->setNbPeople($intNbPeople);
                $objReservation->setCreatedBy($this->getUser());
                $objReservation->setCreatedAt(new DateTimeImmutable('now'));

                $entityManager->persist($objReservation);
                $entityManager->flush();

                $this->addFlash('success', "Votre réservation a été enregistrée");
                return $this->redirectToRoute('app_reservation_index');
            }

            $this->addFlash('danger', "Le jeton de sécurité n'est pas valide. Réessayez ou actualisez la pagee");
            return $this->redirectToRoute('app_reservation_create');
        }

        return $this->render('reservation/create.html.twig', [
            'selectedDate' => $objSelectedDate->format('Y-m-d'),
            'timeSlots'    => $arrTimeSlots,
        ]);
    }

    /**
     * Method to display all the reservations
     * @param ReservationRepository $reservationRepository To collect the reservations
     * @return Response The list of the reservations
     */
    #[Route('/show', name: 'show')]
    #[IsGranted('ROLE_MODO')]
    public function show(ReservationRepository $reservationRepository): Response
    {
        $arrReservation = $reservationRepository->findBy([], ['timeSlot' => 'DESC']);

        return $this->render('reservation/show.html.twig', [
            'reservationList'       => $arrReservation,
        ]);
    }

    /**
     * Method to display all today's reservations 
     * @param ReservationRepository $reservationRepository To collect today's reservations
     * @return Response The list of today's reservations
     */
    #[Route('/show/today', name: 'today')]
    #[IsGranted('ROLE_MODO')]
    public function showToday(ReservationRepository $reservationRepository): Response
    {

        $arrReservation = $reservationRepository->findTodayReservation();

        return $this->render('reservation/show.html.twig', [
            'reservationList'  => $arrReservation
        ]);
    }

    /**
     * Method to cancel a reservation
     * @param Reservation $reservation The reservation to cancel
     * @param EntityManagerInterface $entityManager Use to save and change the data
     * @return Response The success or the failure of canceling the reservation 
     */
    #[Route('/{id<\d+>}/cancel', name: 'cancel')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('RESERVATION_CANCEL', subject: 'reservation', message: "Droit insuffisant pour annuler")]
    #[IsCsrfTokenValid('cancel-reservation', '_csrf_token')]
    public function cancel(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {

        try {
            $reservation->setIsCanceled(true);
            $entityManager->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('success', "La réservation a été annulée");
            } else {
                $this->addFlash('success', "Votre réservation a été annulée");
            }
        } catch (Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_reservation_show');
        } else {
            return $this->redirectToRoute('app_reservation_index');
        }
    }

    /**
     * Method to show all the reservations of the connected user
     * @param ReservationRepository $reservationRepository To collect the user's reservation
     * @return Response The list of the user's reservation
     */
    #[Route('/my', name: 'my')]
    #[IsGranted('ROLE_USER')]
    public function myReservation(ReservationRepository $reservationRepository): Response
    {

        $arrMyReservation = $reservationRepository->findMyReservation($this->getUser());

        return $this->render('reservation/user.html.twig', [
            'myReservationList'  => $arrMyReservation
        ]);
    }
}

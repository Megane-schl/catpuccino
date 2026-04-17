<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }


    /**
     * Method to calculate the total of people on a time slot
     * @param DateTimeImmutable $timeSlot The timeslot to verify
     * @return int The result : the total number of people reserved for this time slot
     */
    public function countPeopleForTimeSlot(DateTimeImmutable $timeSlot): int
    {

        $queryBuilder = $this->createQueryBuilder('r')
            ->select('SUM(r.nbPeople)')
            ->andWhere('r.timeSlot = :timeSlot')
            ->andWhere('r.isCanceled = false')
            ->setParameter('timeSlot', $timeSlot);

        // 0 or i get an error when it's null
        return ($queryBuilder->getQuery()->getSingleScalarResult() ?? 0); //<-- to return the result of a calcul 
    }

    /**
     * Method to show only the futures reservations
     * @return array The futures reservations
     */
    public function findFutureReservation(): array
    {

        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.timeSlot > CURRENT_TIMESTAMP()')
            ->andWhere('r.isCanceled = false')
            ->orderby('r.timeSlot', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * Method to show only the reservations plan for the day
     * @return array The reservations plan today
     */
    public function findTodayReservation(): array
    {

        $today = new \DateTimeImmutable('today');
        $tomorrow = new \DateTimeImmutable('tomorrow');

        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.timeSlot >= :today')
            ->andWhere('r.timeSlot < :tomorrow')
            ->andWhere('r.isCanceled = false')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderby('r.timeSlot', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Method to show only the reservations plan for the day
     * @return array The reservations plan today
     */
    public function findAllActive(): array
    {

        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.isCanceled = false')
            ->orderby('r.timeSlot', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Method to find all the reservations created by a specific user
     * @param User $user The user entity to find the user
     * @return array The list of the user's reservation
     */
    public function findMyReservation(User $user): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.createdBy = :user')
            ->setParameter('user', $user)
            ->orderBy('r.timeSlot', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

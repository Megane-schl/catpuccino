<?php

namespace App\Repository;

use App\Entity\Reservation;
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
            ->andWhere('r.timeSlot = :slot ')
            ->andWhere('r.isCanceled = false')
            ->setParameter('slot', $timeSlot);

        // 0 or i get an error when it's null
        return ($queryBuilder->getQuery()->getSingleScalarResult() ?? 0); //<-- to return the result of a calcul 
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

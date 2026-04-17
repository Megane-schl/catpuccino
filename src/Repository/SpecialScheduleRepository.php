<?php

namespace App\Repository;

use App\Entity\SpecialSchedule;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpecialSchedule>
 */
class SpecialScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialSchedule::class);
    }

    /**
     * Method to show all the specials schedules that was not soft deleted
     * @return array the list of the active specials schedules
     */
    public function findAllActive(): array
    {
        $queryBuilder = $this->createQueryBuilder('sp')
            ->where('sp.deletedAt is NULL')
            ->orderBy('sp.date', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Find a special schedule for a specific date (excluding the soft deleted)
     * @param DateTimeImmutable $date The date to search for
     * @return SpecialSchedule|null The special schedule is found or not or null
     */
    public function findOneByDate(DateTimeImmutable $date): ?SpecialSchedule
    {
        $queryBuilder = $this->createQueryBuilder('sp')
            ->where('sp.date = :date')
            ->andWhere('sp.deletedAt IS NULL')
            ->setParameter('date', $date);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }


    //    /**
    //     * @return SpecialSchedule[] Returns an array of SpecialSchedule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SpecialSchedule
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\Cat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cat>
 */
class CatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cat::class);
    }

    /**
     * Method to show all the cats that was not soft deleted
     * @return array the list of the active cats
     */
    public function findAllActive(): array
    {
        $queryBuilder = $this->createQueryBuilder('cat')
            ->where('cat.deletedAt is NULL')
            ->orderBy('cat.id', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * Method to display the latest cats
     * @param int $intLimit The number to choose how much cats we want to display
     * @return array The list of the latest cats
     */
    public function findNewsCats(int $intLimit): array
    {
        $queryBuilder = $this->createQueryBuilder('cat')
            ->where('cat.deletedAt is NULL')
            ->orderBy('cat.createdAt', 'DESC')
            ->setMaxResults($intLimit);

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Cat[] Returns an array of Cat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cat
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Method to show all the products that was not soft deleted
     * @return array the list of the active products
     */
    public function findAllActive(): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.deletedAt is NULL')
            ->orderBy('p.id', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Method to show unique allergens for a specific product
     * @param int $id The id's product
     * @return array The list of unique allergen found in the product's ingredients
     */
    public function findUniqueAllergensInProduct(int $id): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('DISTINCT al.name')
            ->join('p.ingredients', 'i')
            ->join('i.allergen', 'al')
            ->where('p.id = :id ')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

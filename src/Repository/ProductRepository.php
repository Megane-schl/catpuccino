<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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


    // can't use it in the paginator

    // public function ProductIsVegan()
    // {

    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //         SELECT p.*
    //         FROM products p 
    //         WHERE p.product_id NOT IN (
    //         SELECT pi.prd_ing_product
    //             FROM products_ingredients pi
    //             JOIN ingredients i ON pi.prd_ing_ingredient = i.ingredient_id
    //             WHERE i.ingredient_is_vegan = false
    //         )';

    //     $resultSet = $conn->executeQuery($sql);

    // 
    //     return $resultSet->fetchAllAssociative();
    // }

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

    /**
     * Method that build the query builder used for pagination 
     * @param string $name The search product to filter by name, category or ingredients
     * @param bool $isVegan To filter by if a product is vegan
     * @param bool $isGlutenFree To filter by if a product is gluten free
     * @param bool $isGlutenFree To filter by if a product is lactose free
     * @param int $category To filter by category
     * @return QueryBuilder The query builder for the paginator
     */
    public function createPaginationQuery(?string $name = null, $isVegan = false, $isGlutenFree = false, $isLactoseFree = false, $category = 0): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.deletedAt is NULL')
            ->orderby('p.createdAt', 'DESC')
            ->leftJoin('p.ingredients', 'i')
            ->leftJoin('i.allergen', 'al')
            ->groupBy('p.id');

        if ($isVegan) {
            // exclude product that have ingredients no vegan
            $queryBuilder->having("SUM(CASE WHEN i.isVegan = false THEN 1 ELSE 0 END) = 0");
        }

        if ($isGlutenFree) {
            // count product that contain an ingredient with Gluten
            $queryBuilder->andHaving("SUM(CASE WHEN al.name = 'Gluten' THEN 1 ELSE 0 END) = 0");
        }

        if ($isLactoseFree) {
            // count product that contain an ingredient with Milk
            $queryBuilder->andHaving("SUM(CASE WHEN al.name = 'Lait' THEN 1 ELSE 0 END) = 0");
        }

        //filter on category
        if ($category > 0) {
            $queryBuilder->andWhere('p.category = :catId')
                ->setParameter('catId', $category);
        }

        if ($name) {

            $arrSearchSegments = explode(' ', $name);

            $queryBuilder->join('p.category', 'cy');

            for ($i = 0; $i < count($arrSearchSegments); $i++) {

                if (trim($arrSearchSegments[$i]) != '') {

                    $queryBuilder->andWhere("LOWER(p.name) LIKE LOWER(:value_$i) OR LOWER(cy.name) LIKE LOWER(:value_$i) 
                    OR LOWER(i.name) LIKE LOWER (:value_$i)")
                        ->setParameter("value_$i", '%' . $arrSearchSegments[$i] . '%');
                }
            }
        }

        return $queryBuilder;
    }

    /**
     * Method to display the latest products
     * @param int $intLimit The number to choose how much latest products we want to display
     * @return array The list of the latest products
     */
    public function findNewsProducts(int $intLimit): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.deletedAt is NULL')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($intLimit);

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

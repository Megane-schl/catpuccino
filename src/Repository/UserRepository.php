<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Method that build the query builder used for pagination 
     * @param string $name The search product to filter by firstname and lastname or email
     * @return QueryBuilder The query builder for the paginator
     */
    public function createPaginationQuery(?string $name = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.deletedAt is NULL')
            ->orderby('u.id', 'DESC');

        if ($name) {

            $arrSearchSegments = explode(' ', $name);


            for ($i = 0; $i < count($arrSearchSegments); $i++) {
                if (trim($arrSearchSegments[$i]) != '') {

                    $queryBuilder->andWhere("LOWER(u.firstname) LIKE LOWER(:value_$i) OR LOWER(u.lastname) LIKE LOWER(:value_$i) 
                    OR LOWER(u.email) LIKE LOWER (:value_$i)")
                        ->setParameter("value_$i", '%' . $arrSearchSegments[$i] . '%');
                }
            }
        }

        return $queryBuilder;
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

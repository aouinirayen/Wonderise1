<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    //    /**
    //     * @return Rating[] Returns an array of Rating objects
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

    //    public function findOneBySomeField($value): ?Rating
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Find average rating for a specific item
     */
    public function findAverageRatingByItemId(int $itemId): ?float
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.value) as avgRating')
            ->where('r.itemId = :itemId')
            ->setParameter('itemId', $itemId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find all ratings for a specific item
     */
    public function findByItemId(int $itemId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.itemId = :itemId')
            ->setParameter('itemId', $itemId)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find user's rating for a specific item
     */
    public function findOneByUserAndItemId(int $userId, int $itemId): ?Rating
    {
        return $this->createQueryBuilder('r')
            ->where('r.userId = :userId')
            ->andWhere('r.itemId = :itemId')
            ->setParameter('userId', $userId)
            ->setParameter('itemId', $itemId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\EntityTags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntityTags|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityTags|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityTags[]    findAll()
 * @method EntityTags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityTagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityTags::class);
    }

    // /**
    //  * @return EntityTags[] Returns an array of EntityTags objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EntityTags
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

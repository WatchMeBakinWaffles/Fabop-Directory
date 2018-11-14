<?php

namespace App\Repository;

use App\Entity\EntityShows;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityShows|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityShows|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityShows[]    findAll()
 * @method EntityShows[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityShowsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityShows::class);
    }

    // /**
    //  * @return EntityShows[] Returns an array of EntityShows objects
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
    public function findOneBySomeField($value): ?EntityShows
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

<?php

namespace App\Repository;

use App\Entity\EntityPerformances;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityPerformances|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityPerformances|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityPerformances[]    findAll()
 * @method EntityPerformances[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityPerformancesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityPerformances::class);
    }

    // /**
    //  * @return EntityPerformances[] Returns an array of EntityPerformances objects
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
    public function findOneBySomeField($value): ?EntityPerformances
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

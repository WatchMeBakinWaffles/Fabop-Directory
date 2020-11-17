<?php

namespace App\Repository;

use App\Entity\EntityModele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntityModele|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityModele|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityModele[]    findAll()
 * @method EntityModele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityModeleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityModele::class);
    }

    // /**
    //  * @return EntityModele[] Returns an array of EntityModele objects
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
    public function findOneBySomeField($value): ?EntityModele
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

<?php

namespace App\Repository;

use App\Entity\EntityUserPermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntityUserPermissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityUserPermissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityUserPermissions[]    findAll()
 * @method EntityUserPermissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityUserPermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityUserPermissions::class);
    }

    // /**
    //  * @return UserPermissions[] Returns an array of UserPermissions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPermissions
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

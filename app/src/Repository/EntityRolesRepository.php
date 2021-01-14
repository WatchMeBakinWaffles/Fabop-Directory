<?php

namespace App\Repository;

use App\Entity\EntityRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntityRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityRoles[]    findAll()
 * @method EntityRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityRolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityRoles::class);
    }

    // /**
    //  * @return EntityRoles[] Returns an array of EntityRoles objects
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
    public function findOneBySomeField($value): ?EntityRoles
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

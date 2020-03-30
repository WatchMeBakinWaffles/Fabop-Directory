<?php

namespace App\Repository;

use App\Entity\EntityInstitutions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityInstitutions|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityInstitutions|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityInstitutions[]    findAll()
 * @method EntityInstitutions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityInstitutionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityInstitutions::class);
    }

    public function countAll()
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return EntityInstitutions[] Returns an array of EntityInstitutions objects
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
    public function findOneBySomeField($value): ?EntityInstitutions
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

<?php

namespace App\Repository;

use App\Entity\EntityPeople;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntityPeople|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityPeople|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityPeople[]    findAll()
 * @method EntityPeople[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityPeopleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntityPeople::class);
    }

    // /**
    //  * @return EntityPeople[] Returns an array of EntityPeople objects
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
    public function findOneBySomeField($value): ?EntityPeople
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

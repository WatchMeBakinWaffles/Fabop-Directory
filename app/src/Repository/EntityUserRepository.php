<?php

namespace App\Repository;

use App\Entity\EntityUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityUser[]    findAll()
 * @method EntityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityUser::class);
    }

    // /**
    //  * @return EntityUser[] Returns an array of EntityUser objects
    //  */
    
    public function findByEmail($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.email = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByemail($value): ?EntityUser
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}

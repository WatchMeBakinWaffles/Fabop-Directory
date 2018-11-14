<?php

namespace App\Repository;

use App\Entity\TagsAffect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TagsAffect|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagsAffect|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagsAffect[]    findAll()
 * @method TagsAffect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsAffectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TagsAffect::class);
    }

    // /**
    //  * @return TagsAffect[] Returns an array of TagsAffect objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagsAffect
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

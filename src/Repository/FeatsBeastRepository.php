<?php

namespace App\Repository;

use App\Entity\FeatsBeast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FeatsBeast|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeatsBeast|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeatsBeast[]    findAll()
 * @method FeatsBeast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatsBeastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeatsBeast::class);
    }

    // /**
    //  * @return FeatsBeast[] Returns an array of FeatsBeast objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeatsBeast
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

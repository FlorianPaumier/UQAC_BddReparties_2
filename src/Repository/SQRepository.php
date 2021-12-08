<?php

namespace App\Repository;

use App\Entity\SQ;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SQ|null find($id, $lockMode = null, $lockVersion = null)
 * @method SQ|null findOneBy(array $criteria, array $orderBy = null)
 * @method SQ[]    findAll()
 * @method SQ[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SQRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SQ::class);
    }

    // /**
    //  * @return SQ[] Returns an array of SQ objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SQ
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

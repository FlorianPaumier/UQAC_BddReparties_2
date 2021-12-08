<?php

namespace App\Repository;

use App\Entity\BeastType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BeastType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BeastType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BeastType[]    findAll()
 * @method BeastType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeastTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeastType::class);
    }

    // /**
    //  * @return BeastType[] Returns an array of BeastType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BeastType
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\BeastSubType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BeastSubType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BeastSubType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BeastSubType[]    findAll()
 * @method BeastSubType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeastSubTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeastSubType::class);
    }

    // /**
    //  * @return BeastSubType[] Returns an array of BeastSubType objects
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
    public function findOneBySomeField($value): ?BeastSubType
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

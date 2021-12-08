<?php

namespace App\Repository;

use App\Entity\BeastStatistique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BeastStatistique|null find($id, $lockMode = null, $lockVersion = null)
 * @method BeastStatistique|null findOneBy(array $criteria, array $orderBy = null)
 * @method BeastStatistique[]    findAll()
 * @method BeastStatistique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeastStatistiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeastStatistique::class);
    }

    // /**
    //  * @return BeastStatistique[] Returns an array of BeastStatistique objects
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
    public function findOneBySomeField($value): ?BeastStatistique
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

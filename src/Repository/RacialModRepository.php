<?php

namespace App\Repository;

use App\Entity\RacialMod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RacialMod|null find($id, $lockMode = null, $lockVersion = null)
 * @method RacialMod|null findOneBy(array $criteria, array $orderBy = null)
 * @method RacialMod[]    findAll()
 * @method RacialMod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacialModRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RacialMod::class);
    }

    // /**
    //  * @return RacialMod[] Returns an array of RacialMod objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RacialMod
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

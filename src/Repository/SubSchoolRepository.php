<?php

namespace App\Repository;

use App\Entity\SubSchool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubSchool|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubSchool|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubSchool[]    findAll()
 * @method SubSchool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubSchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubSchool::class);
    }

    // /**
    //  * @return SubSchool[] Returns an array of SubSchool objects
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
    public function findOneBySomeField($value): ?SubSchool
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

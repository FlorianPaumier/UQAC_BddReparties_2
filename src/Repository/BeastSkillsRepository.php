<?php

namespace App\Repository;

use App\Entity\BeastSkills;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BeastSkills|null find($id, $lockMode = null, $lockVersion = null)
 * @method BeastSkills|null findOneBy(array $criteria, array $orderBy = null)
 * @method BeastSkills[]    findAll()
 * @method BeastSkills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeastSkillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeastSkills::class);
    }

    // /**
    //  * @return BeastSkills[] Returns an array of BeastSkills objects
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
    public function findOneBySomeField($value): ?BeastSkills
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

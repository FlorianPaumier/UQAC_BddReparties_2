<?php

namespace App\Repository;

use App\Entity\ClassSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClassSpell|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassSpell|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassSpell[]    findAll()
 * @method ClassSpell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassSpell::class);
    }

    // /**
    //  * @return ClassSpell[] Returns an array of ClassSpell objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClassSpell
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findAllArray(
    )
    {
        return $this->createQueryBuilder("c")
        ->select("c", "t", "s")
        ->innerJoin("c.classType", "t")
        ->innerJoin("c.spell", "s")
            ->orderBy("s.name", 'ASC')
        ->getQuery()->getArrayResult();
    }
}

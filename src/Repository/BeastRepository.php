<?php

namespace App\Repository;

use App\Entity\Beast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Beast|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beast|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beast[]    findAll()
 * @method Beast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beast::class);
    }

    // /**
    //  * @return Beast[] Returns an array of Beast objects
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
    public function findOneBySomeField($value): ?Beast
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getIndexList(
        array $search
    )
    {

        $q = $this->createQueryBuilder("b")
            ->select("b.id",
                "b.name", "t.value as typeName", 'st.name as subTypeName',
                "b.description")
            ->leftJoin("b.type",
                "t")
            ->leftJoin("b.subTypes",
                "st");

        $params = [];

        if (!empty($search["name"])) {
            $q->where($q->expr()->andX("lower(b.name) like lower(:name)"));
            $params[":name"] = '%' . $search["name"] . '%';
        }

        if (!empty($search["start_with"])) {
            $q->where($q->expr()->andX("lower(b.name) like lower(:name)"));
            $params[":name"] = $search["start_with"] . '%';
        }

        if (!empty($search['types'])) {
            $types = explode(",", $search["types"]);
            $where = array_map(function ($type) use ($q) {
                if ($type === '') {
                    return '';
                }
                return "t.value = :$type";
            }, $types);

            $q->andWhere("(" . implode(" OR ", $where) . ")");
            foreach ($types as $typeName) {
                $params[":$typeName"] = $typeName;
            }
        }
        if (!empty($search['subTypes'])) {
            $subTypes = explode(",", $search["subTypes"]);
            $where = array_map(function ($subTypesName) use ($q) {
                if ($subTypesName === '') {
                    return '';
                }
                return "st.name = :$subTypesName";
            }, $subTypes);
            $q->andWhere("(" . implode(" OR ", $where) . ")");
            foreach ($subTypes as $subTypesName) {
                $params[":$subTypesName"] = $subTypesName;
            }
        }

        if (!empty($search["cr"])) {
            $sql = "";
            switch ($search["cr"]){
                case "0":
                    $sql = "b.cr = :cr";
                    $params[":cr"] = $search["cr"];
                    break;
                case "21":
                    $sql = "b.cr >= :cr";
                    $params[":cr"] = $search["cr"];
                    break;
                default:
                    $sql = "b.cr = :cr1 or b.cr = :cr2";
                    $params[":cr1"] = $search["cr"];
                    $params[":cr2"] = intval($search["cr"]) - 1;
                    break;
            }
            $q->where($q->expr()->andX($sql));
        }
        dump($params);
        $q->setParameters($params);
        dump($q->getQuery()->getSQL());
        return $q;
    }
}

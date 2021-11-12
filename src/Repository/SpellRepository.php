<?php

namespace App\Repository;

use App\Entity\Spell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;
use function Doctrine\ORM\QueryBuilder;
use function PHPUnit\Framework\isEmpty;

/**
 * @method Spell|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spell|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spell[]    findAll()
 * @method Spell[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpellRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry,
            Spell::class);
    }

    // /**
    //  * @return Spell[] Returns an array of Spell objects
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
    public function findOneBySomeField($value): ?Spell
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getIndexList(
        ParamFetcherInterface $paramFetcher
    ) {
        $q = $this->createQueryBuilder("s")
            ->select("s.id",
                "s.name",
                's.image',
                "school.name as schoolName",
                "subSchool.name as subSchoolName",
                "s.shortDescription")
            ->innerJoin("s.school",
                "school")
            ->innerJoin("s.subSchool",
                "subSchool");

        if ($paramFetcher->get("filter_alpha")) {
            dump($paramFetcher->get("filter_alpha"));
            $q->where("s.name like :letter")
                ->setParameter("letter",
                    $paramFetcher->get("filter_alpha") . "%");
        }

        return $q;
    }

    public function getSearch(
        array $search
    ) {
        $q = $this->createQueryBuilder("s")
            ->select("s.id",
                "s.name",
                's.image',
                's.description',
                "school.name as schoolName",
                "subSchool.name as subSchoolName",
                "s.shortDescription")
            ->innerJoin("s.school",
                "school")
            ->innerJoin("s.subSchool",
                "subSchool");


        $params = [];

        if (!empty($search["name"])) {
            $q->where($q->expr()->andX("lower(s.name) like lower(:name)"));
            $params[":name"] = '%' . $search["name"] . '%';
        }

        if (!empty($search["start_with"])) {
            $q->where($q->expr()->andX("lower(s.name) like lower(:name)"));
            $params[":name"] = $search["start_with"] . '%';
        }

        if (!empty($search['schools'])) {
            $schools = explode(",", $search["schools"]);
            $where = array_map(function ($school) use ($q) {
                if ($school === '') {
                    return '';
                }
                return "school.name = :$school";
            }, $schools);

            $q->andWhere("(" . implode(" OR ", $where) . ")");
            foreach ($schools as $schoolName) {
                $params[":$schoolName"] = $schoolName;
            }
        }
        if (!empty($search['subSchools'])) {
            $subSchools = explode(",", $search["subSchools"]);
            $where = array_map(function ($subSchoolsName) use ($q) {
                if ($subSchoolsName === '') {
                    return '';
                }
                return "subSchool.name = :$subSchoolsName";
            }, $subSchools);
            $q->andWhere("(" . implode(" OR ", $where) . ")");
            foreach ($subSchools as $subSchoolsName) {
                $params[":$subSchoolsName"] = $subSchoolsName;
            }
        }

        dump($params);
        $q->setParameters($params);
        dump($q->getQuery()->getSQL());
        return $q;
    }
}

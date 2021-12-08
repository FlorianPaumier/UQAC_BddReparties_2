<?php

namespace App\Service;

use App\Entity\Beast;
use App\Entity\ClassSpell;
use App\Entity\School;
use App\Entity\Spell;
use App\Repository\BeastRepository;
use App\Repository\ClassSpellRepository;
use App\Repository\ClassTypeRepository;
use App\Repository\SpellRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\PaginatorInterface;

class SearchService
{

    public function __construct(
        private SpellRepository $spellRepository,
        private BeastRepository $beastRepository,
        private ClassSpellRepository $classSpellRepository,
        private ParamFetcherInterface $paramFetcher,
        private PaginatorInterface $paginator
    ) {
    }

    public function search(
        ?string $type,
        ?ParamFetcherInterface $paramFetcher = null
    ): Pagination|array {

        $method = 'get' . ucfirst($type);
        if (!method_exists($this,
            $method)) {
            return [];
        }
        return Pagination::paginate($this->$method($paramFetcher),
            $this->paginator,
            $this->paramFetcher);
    }

    public function getBestiarySpells(
        ParamFetcherInterface $paramFetcher
    ): array
    {
        return $this->beastRepository->createQueryBuilder("b")
            ->select("s",
                "b",
                't')
            ->leftJoin("b.type",
                "t")
            ->leftJoin("b.spells",
                's')
            ->where("lower(b.name) like lower(:name)")
            ->orderBy("b.name", "ASC")
            ->setParameter("name", mb_strtolower($paramFetcher->get("start_with"))."%")
            ->getQuery()->getArrayResult();
    }

    public function getSpellsBestiary(
        ParamFetcherInterface $paramFetcher = null
    ): array|string
    {
        return $this->spellRepository->createQueryBuilder("s")
            ->select("s",
                "b",
                "ss")
            ->leftJoin("s.beasts",
                "b")
            ->leftJoin("s.school",
                "ss")
            ->where("lower(s.name) like lower(:name)")
            ->setParameter("name", mb_strtolower($paramFetcher->get("start_with"))."%")
            ->orderBy("s.name", "ASC")
            ->getQuery()->getArrayResult();
    }

    public function getClassesSpells(
    )
    {
        $results = $this->classSpellRepository->findAllArray();
        $classes = [];

        /** @var ClassSpell $result */
        foreach ($results as $result) {
            $type = $result['classType'];
            if (!key_exists($type['name'], $classes)) {
                $classes[$type['name']] = [
                    "type" => $type,
                    "spells" => []
                ];
            }

            //dump($classes[$type['name']]["spells"]);
            if (!key_exists($result['spell']["name"], $classes[$type['name']]["spells"])) {
                $classes[$type['name']]["spells"][$result["spell"]["name"]] = $result['spell'];
            }
        }

        return $classes;
    }

    public function getSpellsClasses(
        ParamFetcherInterface $paramFetcher = null
    ) {

        $search = [];

        if (!is_null($paramFetcher)) {
            $search = $paramFetcher->all();
        }

        $results = $this->classSpellRepository->findAllArray();
        $classes = [];

        /** @var ClassSpell $result */
        foreach ($results as $result) {
            $type = $result['spell'];
            if (key_exists("start_with",
                    $search) && !str_starts_with($type["name"],
                    $search["start_with"])) {
                    continue;
                }
            if (!key_exists($type['name'],
                $classes)) {
                $classes[$type['name']] = [
                    "spell" => $type,
                    "types" => []
                ];
            }
            if (!in_array($classes[$type['name']]["types"],
                $result['classType'])) {
                $classes[$type['name']]["types"][] = $result['classType'];
            }
        }

        return $classes;
    }

    public function getCustom(
    )
    {
        return [];
    }
}

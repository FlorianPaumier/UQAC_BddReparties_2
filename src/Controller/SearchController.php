<?php

namespace App\Controller;

use App\Repository\BeastRepository;
use App\Repository\ClassTypeRepository;
use App\Repository\ComponentRepository;
use App\Repository\SchoolRepository;
use App\Repository\SpellRepository;
use App\Repository\SubSchoolRepository;
use App\Service\SearchService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Result;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Doctrine\ORM\QueryBuilder;
use function Symfony\Component\String\u;

class SearchController extends AbstractController
{
    /**
     * @param string|null $category
     * @param SearchService $searchService
     * @return Response
     *
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="start_with")
     */
    #[Route('/search/{category}', name: 'search', requirements: [])]
    public function index(
        string $category = null,
        SearchService $searchService,
        ParamFetcherInterface $paramFetcher
    ): Response {
        return $this->render('search/index.html.twig',
            [
                'category' => $category,
                'rows' => $searchService->search($category,
                    $paramFetcher)
            ]);
    }

    /**
     * @param string|null $category
     * @param SearchService $searchService
     * @return \App\Service\Pagination|array
     *
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="start_with")
     * @Rest\View()
     */
    #[Route('api/search/spell-class', name: 'api-search-class', requirements: [])]
    public function searchSpellClass(
        SearchService $searchService,
        ParamFetcherInterface $paramFetcher
    ) {
        return [
            "pagination" => $searchService->search("spellsClasses",
                $paramFetcher)
        ];
    }

    /**     *
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="start_with")
     * @Rest\View()
     */
    #[Route('api/search/spell-filter', name: 'api-search-filter', requirements: [])]
    public function searchSpellFilter(
        SchoolRepository $schoolRepository,
        SubSchoolRepository $subSchoolRepository,
        SpellRepository $spellRepository,
        ComponentRepository $componentRepository
    ) {
        $domains = $spellRepository->createQueryBuilder("s")->select("s.domain")->distinct()->getQuery()->getResult();
        $effects = $spellRepository->createQueryBuilder("s")->select("s.effect")->distinct()->getQuery()->getResult();
        $targets = $spellRepository->createQueryBuilder("s")->select("s.targets")->distinct()->getQuery()->getResult();
        $duration = $spellRepository->createQueryBuilder("s")->select("s.duration")->distinct()->getQuery()->getResult();
        $castingTime = $spellRepository->createQueryBuilder("s")->select("s.castingTime")->distinct()->getQuery()->getResult();
        $schools = $schoolRepository->createQueryBuilder("s")->select("s.name",
            "s.id")->getQuery()->getResult();
        $subSchools = $subSchoolRepository->createQueryBuilder("s")->select("s.name",
            "s.id")->getQuery()->getResult();

        $domainName = [];
        foreach (array_values($domains) as $domain) {
            $names = explode(',',
                $domain["domain"]);

            foreach ($names as $name) {
                if (empty($names)) {
                    continue;
                }
                $domainName[] = trim(explode(' ',
                    trim($name))[0]);
            }
        }

        $schools = array_map(fn(
            $school
        ) => $school["name"],
            $schools);
        $subSchools = array_map(fn(
            $school
        ) => $school["name"],
            $subSchools);
        $targets = array_map(fn(
            $school
        ) => $school["targets"],
            $targets);
        $effects = array_map(fn(
            $school
        ) => $school["effect"],
            $effects);
        $duration = array_map(fn(
            $school
        ) => $school["duration"],
            $duration);
        $castingTime = array_map(fn(
            $school
        ) => $school["castingTime"],
            $castingTime);
        $domainName = array_unique($domainName);

        return [
            'schools' => $schools,
            'subSchools' => $subSchools,
            'effects' => $effects,
            'targets' => $targets,
            'duration' => $duration,
            'castingTime' => $castingTime,
            'domains' => $domainName
        ];
    }

    /**
     * @Rest\View()
     */
    #[Route('api/search/spells', name: 'api-search-spells', requirements: [], methods: ["POST"])]
    public function searchSpellAdvance(
        SpellRepository $spellRepository,
        Request $request
    ) {

        $parameters = json_decode($request->getContent());

        $queryParameters = [];
        $spellQuery = $spellRepository->createQueryBuilder("s")
            ->select(
                "s.id",
                "s.name",
                's.image',
                's.description',
                "school.name as schoolName",
                "subSchool.name as subSchoolName",
                "s.shortDescription")
            ->distinct()
            ->innerJoin("s.school",
                "school")
            ->innerJoin("s.subSchool",
                "subSchool");

        if (!empty($parameters->name)) {
            $spellQuery->where("s.name like :name");
            $queryParameters["name"] = '%' . $parameters->name . '%';
        }
        if (!empty($parameters->description)) {
            $spellQuery->andWhere("s.description like :description");
            $queryParameters["description"] = '%' . $parameters->description . '%';
        }
        if (!empty($parameters->description)) {
            $spellQuery->andWhere("s.shortDescription like :shortDescription");
            $queryParameters["shortDescription"] = '%' . $parameters->description . '%';
        }
        if (!empty($parameters->schools)) {
            $spellQuery->andWhere('school.name in (:schools)');
            $queryParameters["schools"] = $parameters->schools;
        }
        if (!empty($parameters->subSchools)) {
            $spellQuery->andWhere('subSchool.name in (:subSchools)');
            $queryParameters["schools"] = $parameters->subSchools;
        }
        if (!empty($parameters->components)) {
            $spellQuery->innerJoin("s.components",
                'sc');
            foreach ($parameters->components as $component) {
                if (!$component->open) {
                    continue;
                }
                $queryParameters["components"][] = $component->text;
            }
            if ($queryParameters["components"]) {
                $spellQuery->andWhere("sc.value in (:components)");
            }
        }
        if (!empty($parameters->focus)) {
            if ($parameters->focus->divine->open) {
                $spellQuery->orWhere("s.divineFocus = :divine")->setParameter("divine",
                    true);
                $queryParameters["divine"] = $parameters->focus->divine->open;
            }
            if ($parameters->focus->arcane->open) {
                $spellQuery->orWhere("s.focus = :arcane")->setParameter("arcane",
                    true);
                $queryParameters["arcane"] = $parameters->focus->arcane->open;
            }
        }
        if (!empty($parameters->domains)) {
            $queryDomains = [];
            foreach ($parameters->domains as $domain) {
                $key = u($domain)->snake()->toString();
                $queryDomains[] = "s.domain like :$key";
                $queryParameters[$key] = "%$domain%";
            }
            $spellQuery->andWhere(implode(" OR ",
                $queryDomains));
        }
        if (!empty($parameters->effects)) {
            $queryEffects = [];
            foreach ($parameters->effects as $effect) {
                $key = "e" . u($effect)->snake()->toString();
                $queryEffects[] = "s.effect like :$key";
                $queryParameters[$key] = $effect;

            }
            $spellQuery->andWhere(implode(" OR ",
                $queryEffects));
        }
        if (!empty($parameters->targets)) {
            $queryTarget = [];
            foreach ($parameters->targets as $target) {
                $key = "e" . u($target)->snake()->toString();
                $queryTarget[] = "s.targets = :$key";
                $queryParameters[$key] = $target;
            }
            $spellQuery->andWhere(implode(" OR ",
                $queryTarget));
        }
        if (!empty($parameters->duration)) {
            $queryDuration = [];
            foreach ($parameters->duration as $duration) {
                $key = "e" . u($duration)->snake()->toString();
                $queryDuration[] = "s.duration = :$key";
                $queryParameters[$key] = $duration;

            }
            $spellQuery->andWhere(implode(" OR ",
                $queryDuration));
        }
        if (!empty($parameters->castingTime)) {
            $querycastingTime = [];
            foreach ($parameters->castingTime as $castingTime) {
                $key = "ct" . u($castingTime)->snake();
                $querycastingTime[] = "s.castingTime = :$key";
                $queryParameters[$key] = $castingTime;
            }
            $spellQuery->andWhere(implode(" OR ",
                $querycastingTime));
        }
        if (!empty($parameters->level)) {
            if (!empty($parameters->level->min)) {
                $spellQuery->innerJoin("s.classSpells",
                    "cs");
                $spellQuery->andWhere("cs.level >= :min");
                $queryParameters["min"] = $parameters->level->min;
            }
            if (!empty($parameters->level->max)) {
                $spellQuery->andWhere("cs.level <= :max");
                $queryParameters["max"] = $parameters->level->max;
            }
        }

        $spellQuery->setParameters($queryParameters);
        $spells = $spellQuery->getQuery()->getArrayResult();
        $spellIds = array_map(function (
            $spell
        ) {
            return $spell["id"];
        },
            $spells);
        $spellResult = [];

        if (!empty($parameters->fullText)) {
            /** @var Connection $conn */
            $conn = $this->getDoctrine()
                ->getConnection();

            $sql = "SELECT spell.id as id, ts_rank_cd(documents, :fullTextRank) as rank 
                    FROM spell
                    WHERE documents @@ to_tsquery(:fullText) 
                    order by rank DESC";

            /** @var Statement $stmt */
            $stmt = $conn->prepare($sql);
            /** @var Result $result */
            $result = $stmt->execute([
                "fullText" => $parameters->fullText,
                "fullTextRank" => $parameters->fullText
            ]);

            $fullTextResullt = $result->fetchAllAssociative();

            foreach ($fullTextResullt as $spellRank) {
                dump($spellRank);
                foreach ($spells as $spell) {
                    if ($spellRank["id"] == $spell["id"]) {
                        $spellResult[] = $spell;
                        break;
                    }
                }
            }
        }else{
            $spellResult = $spells;
        }

        return [
            'pagination' => $spellResult,
        ];
    }

    #[Route("/search/global/{search}", name: "search_global")]
    public function globalSearch(
        BeastRepository $beastRepository,
        SpellRepository $spellRepository,
        ClassTypeRepository $classTypeRepository,
        string $search
    ) {

        $beastsSearch = $beastRepository->fullText($search);
        $spells = $spellRepository->fullText($search);

        $beasts = [];
        $beastsId = [];

        foreach ($beastsSearch as $beast){
            if (!in_array($beast["id"], $beastsId)) {
                $beasts[] = ["id" => $beast["id"], "name" => $beast["beast"]];
                $beastsId[] = $beast["id"];
            }
        }

        return $this->render("search/global.html.twig", [
            "beasts" => $beasts,
            "spells" => $spells,
        ]);
    }
}

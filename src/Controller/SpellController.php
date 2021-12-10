<?php

namespace App\Controller;

use App\Entity\School;
use App\Entity\Spell;
use App\Entity\SubSchool;
use App\Form\SpellType;
use App\Repository\ComponentRepository;
use App\Repository\SchoolRepository;
use App\Repository\SpellRepository;
use App\Repository\SubSchoolRepository;
use App\Service\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

#[Route('')]
class SpellController extends AbstractController
{
    /**
     * @param SpellRepository $spellRepository
     * @return Response
     * @Rest\QueryParam(name="sort", default="id")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="filter_alpha")
     */
    #[Route('/spell', name: 'spell_index', methods: ['GET'])]
    public function index(
    ): Response
    {
        return $this->render('spell/index.html.twig',
            []);
    }

    #[Route("/spell-graph")]
    public function spellGraph(
    )
    {
        return $this->render("spell/graph.html.twig");
    }

    /**
     * @param SpellRepository $spellRepository
     * @return array
     * @Rest\View()
     */
    #[Rest\Route("/api/spell/graph")]
    public function spellGraphData(
        SpellRepository $spellRepository
    ) {
        $spells = $spellRepository->createQueryBuilder("s")
            ->select("s.id as id","s.name as spell", "b.name as beast")
            ->leftJoin("s.beasts", "b")
//            ->where("s.name = :name")
//            ->setParameter("name", "Mage Hand")
            ->groupBy("spell", "beast", "id")
            ->getQuery()->getArrayResult();

        $spellIndex = [];
        $data = [];
        $beastsNode = [];
        foreach ($spells as $spell){
            if (!$spell["beast"]) {
                continue;
            }
            if (!in_array($spell["spell"], $spellIndex)) {
                $spellIndex[] = $spell["spell"];
            }
            $beastsNode[$spell["beast"]] = ["id" => $spell["beast"], "group" => $spell["spell"]];
            $data["links"][] = [
                "source" => $spell["spell"],
                "target" => $spell["beast"],
                "value" => 1
            ];
        }
        $data["nodes"] = array_values($beastsNode);
        foreach ($spellIndex as $value) $data["nodes"][] = ["id" => $value, "group" => $value];

        return ["data" => $data];
    }
    /**
     * @param SpellRepository $spellRepository
     * @param SchoolRepository $schoolRepository
     * @param SubSchoolRepository $subSchoolRepository
     * @param ParamFetcherInterface $paramFetcher
     * @param PaginatorInterface $pagination
     * @Rest\View(serializerGroups={"school_light", "subSchool_light"})
     */
    #[Route("/api/spell/spells_filter", name: "spells_filter", methods: ["GET"])]
    public function spellsFilter(
        SchoolRepository $schoolRepository,
        SubSchoolRepository $subSchoolRepository
    ) {
        $schools = $schoolRepository->createQueryBuilder("s")->select("s.name",
            "s.id")->getQuery()->getResult();
        $subSchools = $subSchoolRepository->createQueryBuilder("s")->select("s.name",
            "s.id")->getQuery()->getResult();

        return [
            'schools' => $schools,
            'subSchools' => $subSchools
        ];
    }

    /**
     *
     *
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="name")
     * @Rest\QueryParam(name="schools")
     * @Rest\QueryParam(name="subSchools")
     * @Rest\QueryParam(name="start_with")
     * @Rest\View(serializerGroups={"spell_light", "pagination"})
     * @param SpellRepository $spellRepository
     * @param SchoolRepository $schoolRepository
     * @param SubSchoolRepository $subSchoolRepository
     * @param ParamFetcherInterface $paramFetcher
     * @param PaginatorInterface $pagination
     */
    #[Route("/api/spell", name: "spell_list", methods: ["GET"])]
    public function getSpells(
        SpellRepository $spellRepository,
        ParamFetcherInterface $paramFetcher,
        PaginatorInterface $pagination
    ) {
        return [
            "pagination" => Pagination::paginate($spellRepository->getSearch($paramFetcher->all()),
                $pagination,
                $paramFetcher)
        ];
    }

    //#[Route('/new', name: 'spell_new', methods: ['GET','POST'])]
    public function new(
        Request $request
    ): Response {
        $spell = new Spell();
        $form = $this->createForm(SpellType::class,
            $spell);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($spell);
            $entityManager->flush();

            return $this->redirectToRoute('spell_index',
                [],
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spell/new.html.twig',
            [
                'spell' => $spell,
                'form' => $form,
            ]);
    }

    #[Route('/spell/{id}', name: 'spell_show', methods: ['GET'])]
    public function show(
        Spell $spell
    ): Response {
        return $this->render('spell/show.html.twig',
            [
                'spell' => $spell,
            ]);
    }

    //#[Route('/{id}/edit', name: 'spell_edit', methods: ['GET','POST'])]
    public function edit(
        Request $request,
        Spell $spell
    ): Response {
        $form = $this->createForm(SpellType::class,
            $spell);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('spell_index',
                [],
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('graph.html.twig',
            [
                'spell' => $spell,
                'form' => $form,
            ]);
    }

    //#[Route('/{id}', name: 'spell_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Spell $spell
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $spell->getId(),
            $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($spell);
            $entityManager->flush();
        }

        return $this->redirectToRoute('spell_index',
            [],
            Response::HTTP_SEE_OTHER);
    }
}

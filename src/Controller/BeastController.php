<?php

namespace App\Controller;

use App\Entity\Beast;
use App\Form\BeastType;
use App\Repository\BeastRepository;
use App\Repository\BeastSubTypeRepository;
use App\Repository\BeastTypeRepository;
use App\Service\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class BeastController extends AbstractController
{
    /**
     * @param BeastRepository $beastRepository
     * @return Response
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="filter_alpha")
     */
    #[Route('beast', name: 'beast_index', methods: ['GET'])]
    public function index(
    ): Response
    {
        return $this->render('beast/index.html.twig');
    }

    /**
     * @param BeastRepository $beastRepository
     * @return array
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="name")
     * @Rest\QueryParam(name="types")
     * @Rest\QueryParam(name="subTypes")
     * @Rest\QueryParam(name="cr")
     * @Rest\QueryParam(name="start_with")
     * @Rest\View(serializerGroups={"beast_light", "pagination"})
     */
    #[Route('api/beast', name: 'beast_search', methods: ['GET'])]
    public function getBeasts(
        BeastRepository $beastRepository,
        PaginatorInterface $paginator,
        ParamFetcherInterface $paramFetcher
    ) {
        return [
            "pagination" => Pagination::paginate($beastRepository->getIndexList($paramFetcher->all()),
                $paginator,
                $paramFetcher)
        ];
    }

    /**
     * @param BeastRepository $beastRepository
     * @return array
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="filter_alpha")
     * @Rest\View(serializerGroups={"type_light", "subType_light"})
     */
    #[Route('api/beast/beasts_filter', name: 'beast_filter', methods: ['GET'])]
    public function getFilter(
        BeastTypeRepository $beastTypeRepository,
        BeastSubTypeRepository $beastSubTypeRepository,
        BeastRepository $beastRepository,
    ) {
        return [
            "types" => $beastTypeRepository->findBy([], ["value" => "ASC"]),
            "subTypes" => $beastSubTypeRepository->findBy([], ["name" => "ASC"])
        ];
    }

    //#[Route('/new', name: 'beast_new', methods: ['GET','POST'])]
    public function new(
        Request $request
    ): Response {
        $beast = new Beast();
        $form = $this->createForm(BeastType::class,
            $beast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beast);
            $entityManager->flush();

            return $this->redirectToRoute('beast_index',
                [],
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('beast/new.html.twig',
            [
                'beast' => $beast,
                'form' => $form,
            ]);
    }

    #[Route('beast/{id}', name: 'beast_show', methods: ['GET'])]
    public function show(
        Beast $beast
    ): Response {
        return $this->render('beast/show.html.twig',
            [
                'beast' => $beast,
            ]);
    }

    //#[Route('/{id}/edit', name: 'beast_edit', methods: ['GET','POST'])]
    public function edit(
        Request $request,
        Beast $beast
    ): Response {
        $form = $this->createForm(BeastType::class,
            $beast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('beast_index',
                [],
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('beast/edit.html.twig',
            [
                'beast' => $beast,
                'form' => $form,
            ]);
    }

    //#[Route('/{id}', name: 'beast_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Beast $beast
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $beast->getId(),
            $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($beast);
            $entityManager->flush();
        }

        return $this->redirectToRoute('beast_index',
            [],
            Response::HTTP_SEE_OTHER);
    }
}

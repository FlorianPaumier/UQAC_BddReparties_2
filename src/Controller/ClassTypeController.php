<?php

namespace App\Controller;

use App\Entity\ClassType;
use App\Form\ClassTypeType;
use App\Repository\ClassTypeRepository;
use App\Service\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/class/type')]
class ClassTypeController extends AbstractController
{
    /**
     * @param ClassTypeRepository $classTypeRepository
     * @param PaginatorInterface $paginator
     * @param ParamFetcherInterface $paramFetcher
     * @return Response
     *
     * @Rest\QueryParam(name="sort", default="name")
     * @Rest\QueryParam(name="direction", default="asc", requirements="asc|desc")
     * @Rest\QueryParam(name="page", default=1, requirements="\d+")
     * @Rest\QueryParam(name="limit", default=20, requirements="\d+")
     * @Rest\QueryParam(name="filter_alpha")
     */
    #[Route('/', name: 'class_type_index', methods: ['GET'])]
    public function index(ClassTypeRepository $classTypeRepository, PaginatorInterface $paginator, ParamFetcherInterface $paramFetcher): Response
    {
        return $this->render('class_type/index.html.twig', [
            'class_types' => Pagination::paginate($classTypeRepository->getIndexList($paramFetcher), $paginator, $paramFetcher),
        ]);
    }

//    #[Route('/new', name: 'class_type_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $classType = new ClassType();
        $form = $this->createForm(ClassTypeType::class, $classType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($classType);
            $entityManager->flush();

            return $this->redirectToRoute('class_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('class_type/new.html.twig', [
            'class_type' => $classType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'class_type_show', methods: ['GET'])]
    public function show(ClassType $classType): Response
    {
        return $this->render('class_type/show.html.twig', [
            'class_type' => $classType,
        ]);
    }

//    #[Route('/{id}/edit', name: 'class_type_edit', methods: ['GET','POST'])]
    public function edit(Request $request, ClassType $classType): Response
    {
        $form = $this->createForm(ClassTypeType::class, $classType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('class_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('class_type/edit.html.twig', [
            'class_type' => $classType,
            'form' => $form,
        ]);
    }

//    #[Route('/{id}', name: 'class_type_delete', methods: ['POST'])]
    public function delete(Request $request, ClassType $classType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($classType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('class_type_index', [], Response::HTTP_SEE_OTHER);
    }
}

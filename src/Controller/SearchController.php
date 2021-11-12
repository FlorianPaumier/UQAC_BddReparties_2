<?php

namespace App\Controller;

use App\Service\SearchService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Rest\QueryParam(name="filter_alpha")
     */
    #[Route('/search/{category}', name: 'search', requirements: [])]
    public function index(string $category = null, SearchService $searchService): Response
    {
        return $this->render('search/index.html.twig', [
            'category' => $category,
            'rows' => $searchService->search($category)
        ]);
    }
}

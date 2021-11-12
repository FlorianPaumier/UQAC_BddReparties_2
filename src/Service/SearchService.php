<?php

namespace App\Service;

use App\Entity\Beast;
use App\Entity\School;
use App\Entity\Spell;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\PaginatorInterface;

class SearchService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParamFetcherInterface $paramFetcher,
        private PaginatorInterface $paginator
    ) {
    }

    public function search(
        ?string $type,
    ): Pagination|array {

        $method = 'get' . ucfirst($type);
        if (!method_exists($this,
            $method)) {
            return [];
        }
        return Pagination::paginate($this->$method(), $this->paginator, $this->paramFetcher);
    }

    public function getBestiarySpells(): QueryBuilder
    {
        $data = $this->entityManager->getRepository(Beast::class)->createQueryBuilder("b")
            ->innerJoin("b.type", "t")
            ->innerJoin("b.spells", 's')
            ->innerJoin("s.school", "ss")
        ;

        dump($this->paramFetcher->all());
        return $data;
    }

    public function getSpellsBestiary(): QueryBuilder
    {
        $data = $this->entityManager->getRepository(Spell::class)->createQueryBuilder("s")
            ->innerJoin("s.beasts", "b")
            ->innerJoin("s.school", "ss")
        ;

        dump($this->paramFetcher->all());
        return $data;
    }
}

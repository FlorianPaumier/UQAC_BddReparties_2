<?php


namespace App\Service;


use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\Annotation as JMS;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class Pagination
 * @package App\Model\Representation
 * @JMS\ExclusionPolicy("all")
 */
class Pagination
{
    public const DEFAULT_LIMIT = 15;
    public const DEFAULT_PAGE  = 1;

    /**
     * @var int
     * @JMS\Expose()
     * @JMS\Groups({"pagination"})
     */
    private $currentPageNumber;

    /**
     * @var int
     * @JMS\Expose()
     * @JMS\Groups({"pagination"})
     */
    private $numItemsPerPage;

    /**
     * @var int
     * @JMS\Expose()
     * @JMS\Groups({"pagination"})
     */
    private $totalCount;

    /**
     * @var int
     * @JMS\Expose()
     * @JMS\Groups({"pagination"})
     */
    private $totalPages;

    /**
     * @var array
     * @JMS\Expose()
     * @JMS\Groups({"pagination"})
     */
    private $items = array();


    /**
     * @param PaginationInterface $pagination
     * @return \App\Model\Representation\Pagination
     */
    public static function createFromPaginationInterface(PaginationInterface $pagination): Pagination
    {
        $representation = (new Pagination())
            ->setNumItemsPerPage($pagination->getItemNumberPerPage())
            ->setCurrentPageNumber($pagination->getCurrentPageNumber())
            ->setTotalPages(ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()))
            ->setTotalCount($pagination->getTotalItemCount())
            ->setItems($pagination->getItems());

        return $representation;
    }

    /**
     * @param mixed $target
     * @param PaginatorInterface $paginator
     * @param ParamFetcher $fetcher
     */
    public static function paginate($target, PaginatorInterface $paginator, ParamFetcher $fetcher): Pagination
    {
        $options = [];

        if ($target instanceof QueryBuilder) {
            $options = [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => $target->getRootAliases()[0].'.'.$fetcher->get('sort'),
                PaginatorInterface::DEFAULT_SORT_DIRECTION  => $fetcher->get('direction'),
            ];
        }

        $pagination = $paginator->paginate($target, $fetcher->get('page'), $fetcher->get('limit'), $options);

        return self::createFromPaginationInterface($pagination);
    }

    /**
     * @return int
     */
    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    /**
     * @param int $currentPageNumber
     * @return \App\Model\Representation\Pagination
     */
    public function setCurrentPageNumber(int $currentPageNumber): Pagination
    {
        $this->currentPageNumber = $currentPageNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumItemsPerPage(): int
    {
        return $this->numItemsPerPage;
    }

    /**
     * @param int $numItemsPerPage
     * @return Pagination
     */
    public function setNumItemsPerPage(int $numItemsPerPage): Pagination
    {
        $this->numItemsPerPage = $numItemsPerPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     * @return Pagination
     */
    public function setTotalCount(int $totalCount): Pagination
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     * @return Pagination
     */
    public function setTotalPages(int $totalPages): Pagination
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return Pagination
     */
    public function setItems(iterable $items): Pagination
    {
        $this->items = $items;

        return $this;
    }
}

<?php

namespace Application\Entity\Db\Finder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\ContextProvider;

/**
 * Description of Intervenant
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractFinder extends QueryBuilder
{
    use ContextProviderAwareTrait;

    /**
     * @var array
     */
    protected $filter = [];

    /**
     * @var bool
     */
    protected $queryCreated = false;

    /**
     *
     * @param EntityManager $em
     * @param ContextProvider $contextProvider
     * @param array $filter
     */
    public function __construct(EntityManager $em, ContextProvider $contextProvider = null, array $filter = [])
    {
        parent::__construct($em);

        $this
                ->setContextProvider($contextProvider)
                ->setFilter($filter);
    }

    /**
     *
     * @return self
     */
    abstract protected function createQuery();

    /**
     *
     * @return Query
     */
    public function getQuery()
    {
        if (!$this->queryCreated) {
            $this->createQuery();
            $this->queryCreated = true;
        }

        return parent::getQuery();
    }

    /**
     *
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     *
     * @param array $filter
     * @return self
     */
    public function setFilter(array $filter = [])
    {
        $this->filter = $filter;
        return $this;
    }
}
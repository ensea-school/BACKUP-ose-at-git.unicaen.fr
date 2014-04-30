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
    
    protected $queryCreated         = false;
    protected $globalContextApplied = false;
    
    /**
     * 
     * @param EntityManager $em
     * @param ContextProvider $contextProvider
     */
    public function __construct(EntityManager $em, ContextProvider $contextProvider = null)
    {
        parent::__construct($em);
        
        $this->setContextProvider($contextProvider);
    }
    
    /**
     * 
     * @return self
     */
    abstract protected function createQuery();
    
    /**
     * 
     * @return self
     */
    abstract protected function applyGlobalContext();
    
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
        if (!$this->globalContextApplied) {
            $this->applyGlobalContext();
            $this->globalContextApplied = true;
        }
        
        return parent::getQuery();
    }
}
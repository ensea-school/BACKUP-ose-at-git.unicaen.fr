<?php

namespace Common\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Doctrine\ORM\EntityManager;

/**
 * Description of BasePlugin
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class BasePlugin extends AbstractPlugin
{
    protected $entityManager;
    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
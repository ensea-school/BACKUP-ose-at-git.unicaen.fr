<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Service abstrait
 *
 * Permet d'accéder facilement aux paramètres globaux de l'application
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AbstractService implements ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;

    /**
     * @var EntityManager
     */
    protected $entityManager;





    /**
     * Retourne le gestionnaire d'entités Doctrine
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (empty($this->entityManager))
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        return $this->entityManager;
    }
}
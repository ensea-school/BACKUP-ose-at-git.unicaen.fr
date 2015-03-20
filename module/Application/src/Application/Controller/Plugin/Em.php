<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Doctrine\ORM\EntityManager;

/**
 * Plugin facilitant l'accès au gestionnaire d'entités Doctrine.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Em extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * @var EntityManager
     */
    protected $em;
    
    /**
     * Point d'entrée.
     * 
     * @param string $name
     * @return EntityManager
     */
    public function __invoke($name = 'orm_default')
    {
        return $this->getEntityManager($name);
    }

    /**
     * Retourne le gestionnaire d'entités.
     * 
     * @param string $name
     * @return \Application\Entity\Db\Repository\IntervenantRepository
     */
    protected function getEntityManager($name)
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->getServiceLocator()->get("doctrine.entitymanager.$name");
        }
        
        return $this->em;
    }
}
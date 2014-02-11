<?php

namespace Import\Model\Processus;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Import implements ServiceManagerAwareInterface
{

    /**
     * @var ServiceManager
     */
    protected $serviceManager;



    /**
     * Import d'une structure
     *
     * @param string $id
     */
    public function structure( $id )
    {
        $entityManager = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        /* @var $entityManager \Doctrine\ORM\EntityManager */

        $source = $this->getServiceManager()->get('importServiceStructure')->get( $id );
        /* @var $source \Import\Model\Entity\Structure\Structure */
        var_dump($source);

        $hydrator = new ClassMethods();
        $data = $hydrator->extract($source);
        var_dump($data);

        $dest = new \Application\Entity\Db\Structure;
        $hydrator->hydrate($data, $dest);

        if (null != $id = $source->getTypeId()){
            $dest->setType( $entityManager->find('\Application\Entity\Db\TypeStructure', $id) );
        }
        if (null != $id = $source->getSourceId()){
            $dest->setSource( $entityManager->find('\Application\Entity\Db\Source', $id) );
        }
        var_dump($dest);
        $entityManager->persist($dest);
        $entityManager->flush();

    }



    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     * @return self
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

}
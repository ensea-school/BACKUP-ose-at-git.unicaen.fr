<?php

namespace Import\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Classe mère des services
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Service implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;


    


    /**
     * Retourne une tableau des résultats de la requête transmise.
     *
     *
     * @param string $sql
     * @param array $params
     * @param string $colRes
     * @return array
     */
    protected function query( $sql, $params=null, $colRes=null )
    {
        $stmt = $this->getEntityManager()->getConnection()->executeQuery( $sql, $params );
        $result = array();
        while($r = $stmt->fetch()){
            if (empty($colRes)) $result[] = $r; else $result[] = $r[$colRes];
        }
        return $result;
    }

    /**
     * exécute un ordre SQL
     *
     * @param string $sql
     * @return integer
     */
    protected function exec( $sql )
    {
        return $this->getEntityManager()->getConnection()->exec($sql);
    }

    /**
     * Retourne le gestionnaire d'entités Doctrine
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (empty($this->entityManager))
            $this->entityManager = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        return $this->entityManager;
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
<?php

namespace Import\Model\Mapper;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Import\Model\Exception\Exception;

/**
 * Mapper
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Mapper implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    protected $mapping = array(
        'searchIntervenant'         => 'Harpege',
        'getIntervenant'            => 'Harpege',
        'getIntervenantPermanent'   => 'Harpege',
        'getIntervenantExterieur'   => 'Harpege',
        'getIntervenantAdresses'    => 'Harpege',
        'getStructureList'          => 'Harpege',
        'getStructure'              => 'Harpege',

        'getEtablissementList'      => 'Apogee',
        'getEtablissement'          => 'Apogee',
    );

    protected $connecteurs = array();





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

    /**
     * Retourne la liste des méthodes avec pour chacunes d'entres elles le connecteur associé.
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Methode magique ...
     *
     * @param string $method Nom de la méthode à appeler
     * @param array $arguments Tableau de paramètres
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        $mapping = $this->getMapping();

        if (!array_key_exists($method, $mapping)){
            throw new Exception('Méthode '.$method.' inconnue');
        }

        if (!array_key_exists($mapping[$method], $this->connecteurs)){
            $connecteurClass = '\\Import\\Model\\Connecteur\\'.$mapping[$method];
            $this->connecteurs[$mapping[$method]] = new $connecteurClass;
            $this->connecteurs[$mapping[$method]]->setServiceManager( $this->getServiceManager() );
        }

        return call_user_func_array(array($this->connecteurs[$mapping[$method]],$method), $arguments );
    }
}
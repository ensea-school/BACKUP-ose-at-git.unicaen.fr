<?php

namespace Import\Model\Connecteur;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Import\Model\Exception;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
abstract class Connecteur implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Retourne le nom du connecteur
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Recherche d'un intervenant à partir des données source
     *
     * @param string @criterion Critère de recherche
     * @return array
     */
    public function searchIntervenant( $criterion )
    {
         throw new Exception('Recherche d\'intervenant non implémentée');
    }

    /**
     *
     * @param string $id Identifiant de l'intervenant
     * @return \Import\Model\Entity\Intervenant\Intervenant
     * @throws Exception
     */
    public function getIntervenant( $id )
    {
         throw new Exception('Récupération d\'un intervenant non implémentée dans le connecteur '.$this->getName());
    }

    /**
     * Retourne la liste des adresses d'un intervenant
     * 
     * @param string $id Identifiant de l'intervenant
     * @return \Import\Model\Entity\Intervenant\Adresse[]
     * @throws Exception
     */
    public function getIntervenantAdresses( $id )
    {
        throw new Exception('Récupération des adresses d\'intervenant non implémentée dans le connecteur '.$this->getName());
    }

    /**
     * Retourne la liste des identifiants source des structures
     * 
     * @return string[]
     * @throws Exception
     */
    public function getStructureList()
    {
        throw new Exception('Récupération des identifiants des structures non implémentée dans le connecteur '.$this->getName());
    }

    /**
     * Retourne une structure à partir de son identifiant
     *
     * @param string $id Identifiant de la structure
     * @return \Import\Model\Entity\Structure\Structure
     * @throws Exception
     */
    public function getStructure( $id )
    {
        throw new Exception('Récupération des identifiants des structures non implémentée dans le connecteur '.$this->getName());
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
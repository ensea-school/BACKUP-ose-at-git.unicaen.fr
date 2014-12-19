<?php

namespace Application\Service\Indicateur;

use Zend\Mvc\Controller\Plugin\Url;
use Application\Service\AbstractService;
use Application\Traits\StructureAwareTrait;
use Application\Entity\Db\Indicateur as IndicateurEntity;

/**
 * Description of SaisieServiceApresContratAvenantIndicateur
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractIndicateurImpl extends AbstractService implements IndicateurImplInterface
{
    use StructureAwareTrait;
    
    protected $titlePattern;
            
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
    
    /**
     * @var IndicateurEntity 
     */
    protected $entity;
    
    /**
     * 
     * @param IndicateurEntity $entity
     * @return self
     */
    public function setEntity(IndicateurEntity $entity)
    {
        $this->entity = $entity;
        
        return $this;
    }
    
    /**
     * 
     * @return IndicateurEntity
     */
    function getEntity()
    {
        return $this->entity;
    }
    
    protected $result;

    /**
     * 
     */
    public function getTitle()
    {
        $title = sprintf($this->titlePattern, $this->getResultCount());
        
        if ($this->getStructure()) {
            $title .= " ({$this->getStructure()})";
        }
        
        return $title;
    }
    
    /**
     * Retourne le plugin Url permettant de générer l'URL associé à un résultat d'indicateur.
     * 
     * @return Url
     */
    protected function getHelperUrl()
    {
        return $this->getServiceLocator()->get('ControllerPluginManager')->get('Url');
    }
}
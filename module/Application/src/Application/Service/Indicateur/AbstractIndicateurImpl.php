<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Service\AbstractService;
use Application\Traits\StructureAwareTrait;
use Zend\Filter\Callback;
use Zend\Filter\FilterInterface;
use Zend\Mvc\Controller\Plugin\Url;

/**
 * Description of SaisieServiceApresContratAvenantIndicateur
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractIndicateurImpl extends AbstractService implements IndicateurImplInterface
{
    use StructureAwareTrait;
    
    protected $singularTitlePattern;
    protected $pluralTitlePattern;
            
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
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $pattern = $this->getResultCount() === 1 ? $this->singularTitlePattern : $this->pluralTitlePattern;
        $title   = sprintf($pattern, $this->getResultCount());
        
        if ($appendStructure && $this->getStructure()) {
            $title .= " ({$this->getStructure()})";
        }
        
        return $title;
    }
    
    /**
     * @var FilterInterface 
     */
    protected $resultFormatter;
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultFormatter()
    {
        if (null === $this->resultFormatter) {
            $toString = function($value) { 
                if (!is_object($value) && settype($value, 'string') !== false 
                        || is_object($value) && method_exists($value, '__toString')) {
                    return (string) $value; 
                }
                return sprintf("Impossible de formatter l'item de type '%s' en chaîne de caractères.", 
                        is_object($value) ? get_class($value) : gettype($value));
            };
            $this->resultFormatter = new Callback($toString);
        }
        
        return $this->resultFormatter;
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
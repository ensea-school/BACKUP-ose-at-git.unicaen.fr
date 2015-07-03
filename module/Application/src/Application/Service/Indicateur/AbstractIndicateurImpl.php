<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Service\AbstractService;
use Application\Traits\StructureAwareTrait;
use Traversable;
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
    protected $indicateurEntity;
    
    /**
     * 
     * @param IndicateurEntity $indicateurEntity
     * @return self
     */
    public function setIndicateurEntity(IndicateurEntity $indicateurEntity)
    {
        $this->indicateurEntity = $indicateurEntity;
        
        return $this;
    }
    
    /**
     * 
     * @return IndicateurEntity
     */
    function getIndicateurEntity()
    {
        return $this->indicateurEntity;
    }

    /**
     * @var Traversable
     */
    protected $result;
    
    /**
     * @var int
     */
    protected $resultCount;
        
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $resultCount = $this->getResultCount();
        $pattern     = $resultCount === 1 ? $this->singularTitlePattern : $this->pluralTitlePattern;
        $title       = sprintf($pattern, $resultCount);
        
        if ($appendStructure && ($structure = $this->getStructure())) {
            $title .= " ($structure)";
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

    /**
     * @var bool
     */
    protected $dirtyResult = true;

    /**
     * @var bool
     */
    protected $dirtyResultCount = true;
    
    /**
     * Met cet objet à l'état "dirty" 
     * (i.e. recalcul nécessaire du résultat et de la taille du résultat).
     * 
     * @return self
     */
    protected function setDirty()
    {
        $this->dirtyResult      = true;
        $this->dirtyResultCount = true;
        
        return $this;
    }
    
    /**
     * Surcharge pour mettre l'indicateur à l'état "dirty" lorsque la structure change.
     * 
     * @param StructureEntity $structure
     * @return self
     */
    public function setStructure(StructureEntity $structure = null)
    {
        if ($structure !== $this->getStructure()) {
            $this->setDirty();
        }
        
        $this->structure = $structure;
        
        return $this;
    }
}
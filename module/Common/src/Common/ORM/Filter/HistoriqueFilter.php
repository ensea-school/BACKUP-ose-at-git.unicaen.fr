<?php

namespace Common\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of HistoriqueFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class HistoriqueFilter extends SQLFilter
{
    protected $enabledEntities = [];

    /**
     *
     * @var \DateTime
     */
    protected $dateObservation = null;


    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the required interface
        if (!$targetEntity->reflClass->implementsInterface('Application\Entity\Db\HistoriqueAwareInterface')) {
            return "";
        }

        if (isset($this->enabledEntities[$targetEntity->name])){
            if ($this->dateObservation){
                $this->setParameter('date_observation', $this->dateObservation, \Doctrine\DBAL\Types\Type::DATETIME);
                return '1 = OSE_DIVERS.COMPRISE_ENTRE('.$targetTableAlias.'.HISTO_CREATION,'.$targetTableAlias.'.HISTO_DESTRUCTION, '.$this->getParameter('date_observation').')';
            }else{
                return '1 = OSE_DIVERS.COMPRISE_ENTRE('.$targetTableAlias.'.HISTO_CREATION,'.$targetTableAlias.'.HISTO_DESTRUCTION)';
            }
        }else{
            return '';
        }
    }

    /**
     * 
     * @return \DateTime
     */
    function getDateObservation()
    {
        return $this->dateObservation;
    }

    /**
     *
     * @param \DateTime $dateObservation
     * @return \Common\ORM\Filter\HistoriqueFilter
     */
    function setDateObservation(\DateTime $dateObservation=null)
    {
        $this->dateObservation = $dateObservation;
        return $this;
    }

    /**
     * Désactive le filtre pour une ou des entités données
     *
     * @param string|string[] $entity
     * @return self
     */
    public function disableForEntity( $entity )
    {
        if (is_array($entity)){
            foreach($entity as $e){
                unset($this->enabledEntities[$e]);
            }
        }else{
            unset($this->enabledEntities[$entity]);
        }
        return $this;
    }

    /**
     * Active le filtre pour une ou des entités données
     *
     * @param string|string[] $entity
     * @return self
     */
    public function enableForEntity($entity)
    {
        if (is_array($entity)){
            foreach($entity as $e){
                $this->enabledEntities[$e] = true;
            }
        }else{
            $this->enabledEntities[$entity] = true;
        }
        return $this;
    }

    /**
     * Initialisation rapide du filtre!!
     * 
     * @param string|string[] $entity
     * @param \DateTime|null $dateObservation
     * @return self
     */
    public function init($entity, $dateObservation=null)
    {
        if ($entity){
            $this->enableForEntity($entity);
        }
        if ($dateObservation){
            $this->setDateObservation($dateObservation);
        }else{
            $this->setDateObservation();
        }
        return $this;
    }
}
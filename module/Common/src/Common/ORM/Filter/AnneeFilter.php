<?php

namespace Common\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Application\Entity\Db\Annee;

/**
 * Description of AnneeFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AnneeFilter extends SQLFilter
{
    use \Application\Traits\AnneeAwareTrait;

    protected $enabledEntities = [];



    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        if (
            $this->getAnnee()
            && isset($this->enabledEntities[$targetEntity->name])
            && $targetEntity->reflClass->implementsInterface('Application\Interfaces\AnneeAwareInterface')
        ){
            return $targetTableAlias.'.ANNEE_ID = '.$this->getAnnee()->getId();
        }else{
            return '';
        }
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
     * @param Annee|null $annee
     * @return self
     */
    public function init($entity, $annee=null)
    {
        if ($entity){
            $this->enableForEntity($entity);
        }
        if ($annee){
            $this->setAnnee($annee);
        }else{
            $this->setAnnee();
        }
        return $this;
    }
}
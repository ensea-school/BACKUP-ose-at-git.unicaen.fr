<?php

namespace Common\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of HistoriqueFilter
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class HistoriqueFilter extends SQLFilter
{
    protected $disabledEntities = [];

    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the required interface
        if (!$targetEntity->reflClass->implementsInterface('Application\Entity\Db\HistoriqueAwareInterface')) {
            return "";
        }

        if (isset($this->disabledEntities[$targetEntity->name])){
            return "";
        }

        return "$targetTableAlias.HISTO_DESTRUCTION IS NULL AND $targetTableAlias.HISTO_DESTRUCTEUR_ID IS NULL";
    }

    /**
     * Désactive le filtre pour une entité donnée
     *
     * @param string $entity
     * @return self
     */
    public function disableForEntity( $entity )
    {
        $this->disabledEntities[$entity] = true;
        return $this;
    }

    /**
     * Réactive le filtre pour une entité donnée
     *
     * @param string $entity
     * @return self
     */
    public function enableForEntity($entity)
    {
        unset($this->disabledEntities[$entity]);
        return $this;
    }

    /**
     * Réactive les filtres pour toutes les entités
     *
     * @return self
     */
    public function enableForAll()
    {
        $this->disabledEntities = [];
        return $this;
    }
}
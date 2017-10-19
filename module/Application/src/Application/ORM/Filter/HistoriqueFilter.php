<?php

namespace Application\ORM\Filter;

use Application\Service\Traits\ContextAwareTrait;
use Doctrine\ORM\Mapping\ClassMetaData;

/**
 * Description of HistoriqueFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class HistoriqueFilter extends AbstractFilter
{
    use ContextAwareTrait;

    protected $enabledEntities = [];



    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the required interface
        if (!$targetEntity->reflClass->implementsInterface('UnicaenApp\Entity\HistoriqueAwareInterface')) {
            return "";
        }

        if (isset($this->enabledEntities[$targetEntity->name])) {
            return $targetTableAlias . '.HISTO_DESTRUCTION IS NULL';
        } else {
            return '';
        }
    }



    /**
     * Désactive le filtre pour une ou des entités données
     *
     * @param string|string[] $entity
     *
     * @return self
     */
    public function disableForEntity($entity)
    {
        if (is_array($entity)) {
            foreach ($entity as $e) {
                unset($this->enabledEntities[$e]);
            }
        } else {
            unset($this->enabledEntities[$entity]);
        }

        return $this;
    }



    /**
     * Active le filtre pour une ou des entités données
     *
     * @param string|string[] $entity
     *
     * @return self
     */
    public function enableForEntity($entity)
    {
        if (is_array($entity)) {
            foreach ($entity as $e) {
                $this->enabledEntities[$e] = true;
            }
        } else {
            $this->enabledEntities[$entity] = true;
        }

        return $this;
    }



    /**
     * Initialisation rapide du filtre!!
     *
     * @param string|string[] $entity
     *
     * @return self
     */
    public function init($entity)
    {
        if ($entity) {
            $this->enableForEntity($entity);
        }

        return $this;
    }
}
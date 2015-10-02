<?php

namespace Common\ORM\Filter;

use Application\Service\Traits\ContextAwareTrait;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

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
            $dateObservation = $this->getServiceContext()->getDateObservation();

            if ($dateObservation) {
                $this->setParameter('date_observation', $dateObservation, \Doctrine\DBAL\Types\Type::DATETIME);

                return '1 = OSE_DIVERS.COMPRISE_ENTRE(' . $targetTableAlias . '.HISTO_CREATION,' . $targetTableAlias . '.HISTO_DESTRUCTION, ' . $this->getParameter('date_observation') . ')';
            } else {
                return '1 = OSE_DIVERS.COMPRISE_ENTRE(' . $targetTableAlias . '.HISTO_CREATION,' . $targetTableAlias . '.HISTO_DESTRUCTION)';
            }
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
    public function init($entity, $dateObservation = null)
    {
        if ($entity) {
            $this->enableForEntity($entity);
        }

        return $this;
    }
}
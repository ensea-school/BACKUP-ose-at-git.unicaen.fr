<?php

namespace Application\ORM\Filter;

use Application\Entity\Db\Annee;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of AbstractFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
abstract class AbstractFilter extends SQLFilter
{
    use ContextServiceAwareTrait;

    protected array $enabledEntities = [];



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



    public function isEnabled(ClassMetaData|string $entity): bool
    {
        if ($entity instanceof ClassMetadata) {
            $entity = $entity->name;
        }

        return isset($this->enabledEntities[$entity]);
    }



    /**
     * Initialisation rapide du filtre!!
     *
     * @param string|string[] $entity
     * @param Annee|null      $annee
     *
     * @return self
     */
    public function init($entity, $annee = null)
    {
        if ($entity) {
            $this->enableForEntity($entity);
        }

        return $this;
    }
}
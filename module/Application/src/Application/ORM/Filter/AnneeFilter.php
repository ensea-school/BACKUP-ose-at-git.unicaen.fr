<?php

namespace Application\ORM\Filter;

use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\Mapping\ClassMetaData;
use Application\Entity\Db\Annee;

/**
 * Description of AnneeFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AnneeFilter extends AbstractFilter
{
    use ContextServiceAwareTrait;

    protected $enabledEntities = [];



    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        if (isset($this->enabledEntities[$targetEntity->name])) {
            return $targetTableAlias . '.ANNEE_ID = ' . $this->getServiceContext()->getAnnee()->getId();
        }

        return '';
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
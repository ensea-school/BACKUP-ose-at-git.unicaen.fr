<?php

namespace Common\ORM\Filter;

use Application\Service\Traits\ContextAwareTrait;
use Doctrine\ORM\Mapping\ClassMetaData;
use Application\Entity\Db\Annee;

/**
 * Description of AnneeFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AnneeFilter extends AbstractFilter
{
    use ContextAwareTrait;

    protected $enabledEntities = [];



    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        if (isset($this->enabledEntities[$targetEntity->name])) {
            if ($targetEntity->name == 'Application\Entity\Db\Etape') {
                return $this->addEtapeFilterConstraint($targetTableAlias);
            } elseif ($targetEntity->reflClass->implementsInterface('Application\Interfaces\AnneeAwareInterface')) {
                return $targetTableAlias . '.ANNEE_ID = ' . $this->getServiceContext()->getAnnee()->getId();
            }
        }

        return '';
    }



    protected function addEtapeFilterConstraint($targetTableAlias)
    {
        $sqldObs = '';
        if ($dateObservation = $this->getServiceContext()->getDateObservation()) {
            $sqldObs = ', ' . $this->getParameter('date_observation');
            $this->setParameter('date_observation', $dateObservation);
        }

        $annee = $this->getServiceContext()->getAnnee()->getId();

        return "
          1 = OSE_DIVERS.COMPRISE_ENTRE($targetTableAlias.HISTO_CREATION,$targetTableAlias.HISTO_DESTRUCTION$sqldObs)
          OR EXISTS(
            SELECT
              cp.etape_id
            FROM
              chemin_pedagogique cp
              JOIN element_pedagogique ep ON ep.id = cp.element_pedagogique_id AND 1 = ose_divers.comprise_entre(cp.histo_creation,cp.histo_destruction$sqldObs)
            WHERE
              1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction$sqldObs)
              AND cp.etape_id = $targetTableAlias.id
              AND ep.annee_id = $annee
          )";
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
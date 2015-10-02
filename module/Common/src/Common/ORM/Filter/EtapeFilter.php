<?php

namespace Common\ORM\Filter;

use Application\Service\Traits\ContextAwareTrait;
use Doctrine\ORM\Mapping\ClassMetaData;

/**
 * Description of EtapeFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeFilter extends AbstractFilter
{
    use ContextAwareTrait;

    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->name != 'Application\Entity\Db\Etape') return '';

        $dateObservation = $this->getServiceContext()->getDateObservation();

        $sqldObs = '';
        if ($dateObservation){
            $sqldObs = ', '.$this->getParameter('date_observation');
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
}
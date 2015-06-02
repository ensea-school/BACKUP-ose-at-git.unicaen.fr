<?php

namespace Common\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of EtapeFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeFilter extends SQLFilter
{
    use \Application\Traits\AnneeAwareTrait;

    /**
     *
     * @var \DateTime
     */
    protected $dateObservation = null;


    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->name != 'Application\Entity\Db\Etape') return '';

        $sqldObs = '';
        if ($this->dateObservation){
            $sqldObs = ', '.$this->getParameter('date_observation');
            $this->setParameter('date_observation', $this->dateObservation);
        }

        $annee = $this->getAnnee()->getId();

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
}
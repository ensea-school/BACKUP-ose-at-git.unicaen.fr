<?php

namespace Contrat\Service;


use Application\Entity\Db\Intervenant;
use Application\Service\AbstractService;
use Contrat\Entity\Db\ContratServiceListe;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of ContratServiceListeService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ContratServiceListeService extends AbstractService
{
    use EntityManagerAwareTrait;

    /**
     * @param Intervenant|null $intervenant
     *
     * @return ContratServiceListe[]|null
     */
    public function getListeServiceContratIntervenant(?Intervenant $intervenant)
    {

        $em     = $this->getEntityManager();
        $dql    = "SELECT csl, i, s, ep, tm, fr FROM " . ContratServiceListe::class . " csl 
            JOIN csl.intervenant i
            LEFT JOIN csl.structure s
            LEFT JOIN csl.elementPedagogique ep
            LEFT JOIN csl.typeMission tm
            LEFT JOIN csl.fonctionReferentiel fr
            WHERE csl.intervenant = :intervenant";
        $query  = $em->createQuery($dql)->setParameter('intervenant', $intervenant);
        $result = $query->getResult();

        return $result;
    }

}


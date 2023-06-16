<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Paiement\Entity\Db\JourFerie;


/**
 * Description of JourFerieController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class JourFerieController extends AbstractController
{
    use ContextServiceAwareTrait;

    public function indexAction()
    {
        $dql = "
        SELECT 
            jf 
        FROM 
            ".JourFerie::class." jf
        WHERE 
            jf.dateJour >= :dateDebut AND jf.dateJour <= :dateFin
        ORDER BY
            jf.dateJour
        ";

        $annee = $this->getServiceContext()->getAnnee();

        $parameters = [
            'dateDebut' => $annee->getDateDebut(),
            'dateFin' => $annee->getDateFin(),
        ];

        $joursFeries = $this->em()->createQuery($dql)->setParameters($parameters)->getResult();

        return compact('annee', 'joursFeries');
    }

}
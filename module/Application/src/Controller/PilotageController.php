<?php

namespace Application\Controller;

use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\PilotageServiceAwareTrait;


/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PilotageController extends AbstractController
{
    use ContextServiceAwareTrait;
    use EtatSortieServiceAwareTrait;
    use PilotageServiceAwareTrait;


    public function indexAction()
    {
        return [];
    }


    public function ecartsEtatsACtion()
    {

        //Contexte année et structure
        $annee = $this->getServiceContext()->getAnnee();
        $structure = $this->getServiceContext()->getStructure();

        $filters['ANNEE_ID'] = $annee->getId();
        if ($structure) {
            $filters['STRUCTURE_ID'] = $structure->getId();
        }
        //On récupére l'état de sortie pour l'export des agréments
        $etatSortie = $this->getServiceEtatSortie()->getRepo()->findOneBy(['code' => 'ecarts-heures-complementaire']);
        $csvModel = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters);
        $csvModel->setFilename('ecarts-heures-complementaires-' . $annee->getId() . '.csv');


        return $csvModel;

    }
}
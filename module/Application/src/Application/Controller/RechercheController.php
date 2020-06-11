<?php

namespace Application\Controller;

use Application\Constants;
use Application\Processus\Traits\IntervenantProcessusAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Zend\View\Model\JsonModel;

/**
 * Description of RechercheController
 *
 */
class RechercheController extends AbstractController
{
    use UtilisateurServiceAwareTrait;
    use IntervenantProcessusAwareTrait;



    public function intervenantFindAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $res = $this->getProcessusIntervenant()->recherche()->rechercherLocalement($term, 50, ':ID');

        $result = [];
        foreach ($res as $key => $r) {
            $feminin = $r['civilite'] != 'Monsieur';

            $civilite        = $feminin ? 'M<sup>me</sup>' : 'M.';
            $nom             = strtoupper($r['nom']);
            $prenom          = ucfirst($r['prenom']);
            $naissance       = 'né' . ($feminin ? 'e' : '') . ' le ' . $r['date-naissance']->format(Constants::DATE_FORMAT);
            $numeroPersonnel = 'N°' . $r['numero-personnel'];
            $structure       = $r['structure'];

            $result[$key] = [
                'id'    => $key,
                'label' => "$nom $prenom",
                'extra' => "<small>($civilite, $naissance, $numeroPersonnel, $structure)</small>",
            ];
        }

        return new JsonModel($result);
    }



    public function utilisateurFindAction()
    {

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $result = $this->getServiceUtilisateur()->rechercheUtilisateurs($term);

        return new JsonModel($result);
    }
}
<?php

namespace Application\Controller;

use Application\Constants;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Intervenant\Processus\IntervenantProcessusAwareTrait;
use Laminas\View\Model\JsonModel;

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
            $feminin = $r['civilite'] == 'Madame';

            $details = [];
            if ($r['civilite']) {
                $details['civilite'] = $feminin ? 'M<sup>me</sup>' : 'M.';
            }
            $details['nom']       = strtoupper($r['nom']);
            $details['prenom']    = ucfirst($r['prenom']);
            $details['naissance'] = 'né' . ($feminin ? 'e' : '') . ' le ' . $r['date-naissance']->format(Constants::DATE_FORMAT);
            $details['code']      = 'N°' . $r['numero-personnel'];
            if ($r['structure']) {
                $details['structure'] = $r['structure'];
            }
            if ($r['statut']) {
                $details['statut'] = $r['statut'];
            }

            $result[$key] = [
                'id'    => $key,
                'label' => $details['nom'] . ' ' . $details['prenom'],
                'extra' => "<small>(" . implode(', ', $details) . ")</small>",
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
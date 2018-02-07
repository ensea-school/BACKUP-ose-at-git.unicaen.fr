<?php

namespace Application\Controller;

use Application\Connecteur\Traits\LdapConnecteurAwareTrait;
use Application\Constants;
use Application\Processus\Traits\IntervenantProcessusAwareTrait;
use Application\Service\StructureService;
use Zend\View\Model\JsonModel;

/**
 * Description of RechercheController
 *
 */
class RechercheController extends AbstractController
{
    use LdapConnecteurAwareTrait;
    use IntervenantProcessusAwareTrait;


    public function intervenantFindAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $res = $this->getProcessusIntervenant()->rechercher($term);

        $result = [];
        foreach ($res as $key => $r) {
            $feminin         = $r['civilite'] != 'Monsieur';

            $civilite        = $feminin ? 'M<sup>me</sup>' : 'M.';
            $nom             = strtoupper($r['nom']);
            $prenom          = ucfirst($r['prenom']);
            $naissance       = 'né'.($feminin ? 'e' : '').' le '.$r['date-naissance']->format(Constants::DATE_FORMAT);
            $numeroPersonnel = 'N°'.$r['numero-personnel'];
            $structure       = $r['structure'];

            $result[$key] = [
                'id'       => $r['numero-personnel'],
                'label'    => "$nom $prenom",
                'extra'    => "<small>($civilite, $naissance, $numeroPersonnel, $structure)</small>",
            ];
        }

        return new JsonModel($result);
    }



    public function utilisateurFindAction()
    {

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $result = @$this->getConnecteurLdap()->rechercheUtilisateurs($term);

        return new JsonModel($result);
    }
}
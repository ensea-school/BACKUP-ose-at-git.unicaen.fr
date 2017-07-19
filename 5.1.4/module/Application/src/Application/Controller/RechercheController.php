<?php

namespace Application\Controller;

use Application\Constants;
use Application\Processus\Traits\IntervenantProcessusAwareTrait;
use Application\Service\Traits\PersonnelAwareTrait;
use Zend\View\Model\JsonModel;

/**
 * Description of RechercheController
 *
 */
class RechercheController extends AbstractController
{
    use PersonnelAwareTrait;
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



    public function personnelFindAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Personnel::class,
        ]);

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $qb = $this->getServicePersonnel()->finderByTerm($term);
        $this->getServicePersonnel()->join('applicationStructure', $qb, 'structure');
        $personnels = $this->getServicePersonnel()->getList($qb);

        $result = [];
        foreach ($personnels as $personnel) {
            $result[$personnel->getId()] = [
                'id'        => $personnel->getId(),
                'label'     => (string)$personnel,
                'structure' => $personnel->getStructure()->getId(),
                'template'  => $personnel . ' <small class="bg-info">n° ' . $personnel->getSourceCode() . ', ' . $personnel->getStructure() . '</small>',
            ];
        };

        return new JsonModel($result);
    }
}
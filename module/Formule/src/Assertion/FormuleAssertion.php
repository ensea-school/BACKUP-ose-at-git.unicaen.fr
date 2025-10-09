<?php

namespace Formule\Assertion;

use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleAssertion extends AbstractAssertion
{



    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        switch ($action) {
            case 'details':
                return $this->assertVisuHC($intervenant);
        }

        return true;
    }





    protected function assertVisuHC(?Intervenant $intervenant): bool
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        return $statut->getServicePrevu() || $statut->getServiceRealise() || $statut->getReferentielPrevu() || $statut->getReferentielRealise();
    }
}
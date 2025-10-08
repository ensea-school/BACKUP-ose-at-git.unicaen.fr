<?php

namespace Formule\Assertion;

use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Utilisateur\Acl\Role;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleAssertion extends AbstractAssertion
{



    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

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
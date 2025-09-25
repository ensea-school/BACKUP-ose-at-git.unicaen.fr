<?php

namespace Formule\Assertion;

use Application\Acl\Role;
use Intervenant\Entity\Db\Intervenant;
use Framework\Authorize\AbstractAssertion;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleAssertion extends AbstractAssertion
{



    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $role        = $this->getRole();
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

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
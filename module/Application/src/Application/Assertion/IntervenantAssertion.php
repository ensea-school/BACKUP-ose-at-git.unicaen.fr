<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Role;
use Application\Entity\Db\WfEtape;
use UnicaenAuth\Assertion\AbstractAssertion;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantAssertion extends AbstractAssertion
{

    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }


    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role        = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        switch ($action) {
            case 'cloturer-saisie':
                return $this->assertClotureSaisie($intervenant);
            break;
        }

        return true;
    }



    protected function assertPage(array $page)
    {
        $role        = $this->getRole();

        if ($role instanceof Role && isset($page['privilege'])){
            return $role->hasPrivilege($page['privilege']);
        }

        return true;
    }



    protected function assertClotureSaisie( Intervenant $intervenant=null )
    {
        if (!$intervenant) return false;
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_CLOTURE_REALISE, $intervenant)){
            return false;
        }
        return true;
    }



    protected function assertEtapeAtteignable($etape, Intervenant $intervenant = null)
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}
<?php

namespace Paiement\Assertion;

use Application\Acl\Role;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\Db\WfEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of PaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;

    /* ---- Routage général ---- */
    public function __invoke (array $page): bool // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController ($controller, $action = null, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // Si c'est bon alors on affine...
        switch ($action) {
            case 'demandemiseenpaiement':
                return $this->assertEtapeAtteignable(WfEtape::CODE_DEMANDE_MEP, $intervenant);
            break;
            case 'visualisationmiseenpaiement':

            break;
            case 'editionmiseenpaiement':

            break;
            case 'etatpaiement':
                if ($role->getIntervenant()) return false; // pas pour les intervenants
            break;
            case 'extractionpaieprime':
                return $this->assertEtapeAtteignable(WfEtape::CODE_MISSION_PRIME, $intervenant);
            break;
            case  'miseenpaiement':

            break;
        }

        return true;
    }


    protected function assertPage (array $page): bool
    {
        if (isset($page['workflow-etape-code'])) {
            $etape       = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        return true;
    }



    protected function assertEtapeAtteignable ($etape, ?Intervenant $intervenant, ?Structure $structure = null): bool
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant, $structure);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}
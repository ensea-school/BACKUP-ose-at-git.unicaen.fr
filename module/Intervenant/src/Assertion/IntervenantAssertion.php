<?php

namespace Intervenant\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;


    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, ?string $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::INTERVENANT_EDITION:
                    case Privileges::INTERVENANT_EDITION_AVANCEE:
                    case Privileges::INTERVENANT_SUPPRESSION:
                        return $this->assertEdition($entity);
                }
            break;
        }

        return true;
    }



    protected function assertPage(array $page): bool
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
            case 'supprimer':
            case 'historiser':
                return $this->assertEdition($intervenant);
            break;
        }

        return true;
    }



    protected function assertEdition(?Intervenant $intervenant = null): bool
    {
        $role = $this->getRole();
        if ($role instanceof Role && $role->getStructure() && $intervenant->getStructure()) {
            return $intervenant->getStructure()->inStructure($role->getStructure());
        }

        return true;
    }



    protected function assertEtapeAtteignable($etape, ?Intervenant $intervenant = null): bool
    {
        if ($intervenant) {
            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
            $workflowEtape = $feuilleDeRoute->get($etape);
            if (!$workflowEtape || !$workflowEtape->isAllowed()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}
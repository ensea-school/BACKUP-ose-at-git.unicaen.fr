<?php

namespace Intervenant\Assertion;

use Application\Provider\Privileges;
use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
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
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

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
        if ($this->getServiceUserContext()->getStructure() && $intervenant->getStructure()) {
            return $intervenant->getStructure()->inStructure($this->getServiceUserContext()->getStructure());
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
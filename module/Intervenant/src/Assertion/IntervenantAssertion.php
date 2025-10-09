<?php

namespace Intervenant\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Unicaen\Framework\Navigation\Page;
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
    use ContextServiceAwareTrait;



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



    protected function assertPage(Page $page): bool
    {
        $page = $page->getData();
        if (isset($page['workflow-etape-code'])) {
            $etape       = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        return true;
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

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
        if ($this->getServiceContext()->getStructure() && $intervenant->getStructure()) {
            return $intervenant->getStructure()->inStructure($this->getServiceContext()->getStructure());
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
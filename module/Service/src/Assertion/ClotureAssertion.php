<?php

namespace Service\Assertion;

use Application\Provider\Privileges;
use Framework\Authorize\AbstractAssertion;
use Framework\Navigation\Page;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of ClotureAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ClotureAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;



    protected function assertEntity(ResourceInterface $entity, ?string $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::CLOTURE_CLOTURE:
                    case Privileges::CLOTURE_REOUVERTURE:
                        return $this->assertCloture($entity);
                }
                break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::CLOTURE_CLOTURE:
                        return $this->assertCloture($entity->getIntervenant());
                    case Privileges::CLOTURE_REOUVERTURE:
                        return $this->assertReouverture($entity->getIntervenant());
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

            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
            $wfEtape        = $feuilleDeRoute->get($etape);

            if (!$wfEtape || !$wfEtape->isAllowed()) {
                return false;
            }
        }

        return true;
    }



    protected function assertCloture(?Intervenant $intervenant = null): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        $wfEtape        = $feuilleDeRoute->get(WorkflowEtape::CLOTURE_REALISE);

        return $this->asserts([
                                  $intervenant,
                                  $wfEtape,
                                  $wfEtape?->isAllowed(),
                              ]);
    }



    protected function assertReouverture(?Intervenant $intervenant = null): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);

        $hasNoDMEP = false;
        if ($intervenant) {
            $dmepEtape = $feuilleDeRoute->get(WorkflowEtape::DEMANDE_MEP);
            $hasNoDMEP = !$dmepEtape || $dmepEtape->realisation == 0;
        }

        $wfEtape = $feuilleDeRoute->get(WorkflowEtape::CLOTURE_REALISE);

        return $this->asserts([
                                  $hasNoDMEP,
                                  $intervenant,
                                  $wfEtape,
                                  $wfEtape?->isAllowed(),
                              ]);
    }

}
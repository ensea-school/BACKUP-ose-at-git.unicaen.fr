<?php

namespace Service\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WfEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of ClotureAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ClotureAssertion extends AbstractAssertion
{
    use ServiceAssertionAwareTrait;
    use WorkflowServiceAwareTrait;

    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

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



    protected function assertPage(array $page)
    {
        if (isset($page['workflow-etape-code'])) {
            $etape       = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$this->getAssertionService()->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        return true;
    }



    protected function assertCloture(Intervenant $intervenant = null)
    {
        return $this->asserts([
            $intervenant,
            $this->getAssertionService()->assertEtapeAtteignable(WfEtape::CODE_CLOTURE_REALISE, $intervenant),
        ]);
    }



    protected function assertReouverture(Intervenant $intervenant = null)
    {
        $hasNoDMEP = false;
        if ($intervenant) {
            $dmepEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_DEMANDE_MEP, $intervenant);
            $hasNoDMEP = !$dmepEtape || $dmepEtape->getFranchie() == 0;
        }

        return $this->asserts([
            $hasNoDMEP,
            $intervenant,
            $this->getAssertionService()->assertEtapeAtteignable(WfEtape::CODE_CLOTURE_REALISE, $intervenant),
        ]);
    }

}
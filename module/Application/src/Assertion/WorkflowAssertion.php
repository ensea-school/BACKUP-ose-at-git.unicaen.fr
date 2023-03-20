<?php

namespace Application\Assertion;

use Application\Entity\Db\Structure;
use Application\Entity\WorkflowEtape;
use Application\Provider\Privilege\Privileges;
use Application\Resource\WorkflowResource;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of WorkflowAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class WorkflowAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;



    /**
     * @param ResourceInterface $resource
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertOther(ResourceInterface $resource = null, $privilege = null)
    {
        switch (true) {
            case $resource instanceof WorkflowEtape:
                return $this->assertWorkflowEtape($resource);

            case $resource instanceof WorkflowResource:
                return $this->assertWorkflowResource($resource);
        }

        return true;
    }



    protected function assertWorkflowResource(WorkflowResource $resource)
    {
        if ($resource->getEtape() instanceof WorkflowEtape) {
            return $this->assertWorkflowEtape($resource->getEtape(), $resource->getStructure());
        } else {
            $etape = $this->getServiceWorkflow()->getEtape($resource->getEtape(), $resource->getIntervenant(), $resource->getStructure());
            if (!$etape) return false; // l'étape n'existe pas ou bien ne concerne pas l'intervenant

            return $this->assertWorkflowEtape($etape, $resource->getStructure());
        }
    }



    protected function assertWorkflowEtape(WorkflowEtape $etape, Structure $structure = null)
    {
        if (!$etape->getStructure() && $structure && $se = $etape->getStructureEtape($structure)) {
            return $se->getAtteignable();
        } else {
            return $etape->isAtteignable();
        }
    }

}
<?php

namespace Workflow\Assertion;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\WorkflowEtape;
use Workflow\Resource\WorkflowResource;
use Workflow\Service\WorkflowServiceAwareTrait;


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
    protected function assertOther(ResourceInterface|string|null $resource = null, $privilege = null): bool
    {
        switch (true) {
            case $resource instanceof WorkflowEtape:
                return $this->assertWorkflowEtape($resource);

            case $resource instanceof WorkflowResource:
                return $this->assertWorkflowResource($resource);
        }

        return true;
    }



    protected function assertWorkflowResource(WorkflowResource $resource): bool
    {
        if ($resource->getEtape() instanceof WorkflowEtape) {
            return $this->assertWorkflowEtape($resource->getEtape(), $resource->getStructure());
        } else {
            $etape = $this->getServiceWorkflow()->getEtape($resource->getEtape(), $resource->getIntervenant(), $resource->getStructure());
            if (!$etape) return false; // l'étape n'existe pas ou bien ne concerne pas l'intervenant

            return $this->assertWorkflowEtape($etape, $resource->getStructure());
        }
    }



    protected function assertWorkflowEtape(WorkflowEtape $etape, ?Structure $structure = null): bool
    {
        if (!$etape->getStructure() && $structure && $se = $etape->getStructureEtape($structure)) {
            return $se->getAtteignable();
        } else {
            return $etape->isAtteignable();
        }
    }

}
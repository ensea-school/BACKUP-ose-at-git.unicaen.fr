<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of ServiceAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ServiceAssertion extends AbstractAssertion
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
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof Service:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_VISUALISATION:
                        return $this->assertServiceVisualisation($role, $entity);
                }
            break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_VALIDATION:
                        return $this->assertServiceValidation($role, $entity);
                    case Privileges::ENSEIGNEMENT_DEVALIDATION:
                        return $this->assertServiceDevalidation($role, $entity);
                }
            break;
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null)
    {
        //$intervenant = $this->getMvcEvent()->getParam('intervenant');

        return true;
    }



    protected function assertPage(array $page)
    {
        if (isset($page['workflow-etape-code'])) {
            $etape = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$this->assertEtapeAtteignable($etape, $intervenant)){
                return false;
            }
        }

        return true;
    }



    protected function assertServiceVisualisation(Role $role, Service $service)
    {
        if (!$this->assertIntervenant($role, $service->getIntervenant())) {
            return false;
        }

        if (!$this->assertEtapeAtteignable($service->getTypeVolumeHoraire(), $service->getIntervenant()))
        {
            return false;
        }
        return true;
    }



    protected function assertServiceValidation( Role $role, Validation $validation )
    {
        return $this->asserts([
            ! $validation->getId(),
            $this->assertIntervenant($role, $validation->getIntervenant()),
            $this->assertStructure($role, $validation->getStructure()),
        ]);
    }



    protected function assertServiceDevalidation( Role $role, Validation $validation )
    {
        return $this->asserts([
            $validation->getId(),
            $this->assertIntervenant($role, $validation->getIntervenant()),
            $this->assertStructure($role, $validation->getStructure()),
        ]);
    }



    protected function assertIntervenant(Role $role, Intervenant $intervenant=null)
    {
        if ($intervenant) {
            if ($ri = $role->getIntervenant()) {
                if ($ri != $intervenant) { // un intervenant ne peut pas voir les services d'un autre
                    return false;
                }
            }
        }
        return true;
    }



    protected function assertStructure(Role $role, Structure $structure=null)
    {
        if ($structure) {
            if ($ri = $role->getStructure()) {
                if ($ri != $structure) { // une structure ne peut pas éditer les services d'une autre
                    return false;
                }
            }
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
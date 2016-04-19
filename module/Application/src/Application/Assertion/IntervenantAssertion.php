<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Role;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantAssertion extends AbstractAssertion
{
    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $role        = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_CLOTURE:
                        return $this->assertClotureSaisie($entity);
                }
            break;
        }

        return true;
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
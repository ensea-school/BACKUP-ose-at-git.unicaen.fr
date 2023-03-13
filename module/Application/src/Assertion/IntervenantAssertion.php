<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Acl\Role;
use Application\Entity\Db\Validation;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


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
                    case Privileges::INTERVENANT_EDITION:
                    case Privileges::INTERVENANT_EDITION_AVANCEE:
                        return $this->assertEdition($entity);
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

            if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role        = $this->getRole();
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch ($action) {
            case 'voir-heures-comp':
                return $this->assertVisuHC($intervenant);
            break;
        }

        return true;
    }



    protected function assertEdition(Intervenant $intervenant = null)
    {
        $role = $this->getRole();
        if ($role instanceof Role && $role->getStructure() && $intervenant->getStructure()) {
            return $role->getStructure() == $intervenant->getStructure();
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



    protected function assertVisuHC(?Intervenant $intervenant)
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        return $statut->getServicePrevu() || $statut->getServiceRealise() || $statut->getReferentielPrevu() || $statut->getReferentielRealise();
    }
}
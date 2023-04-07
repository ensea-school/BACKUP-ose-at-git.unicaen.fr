<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of MissionAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class WorkflowAssertion extends AbstractAssertion
{

    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertPage(array $page)
    {
        $role = $this->getRole();
        /* @var $role Role */

        if (!$role) return false;

        $intervenant = null;
        if (isset($page['workflow-etape-code'])) {
            $etape = $page['workflow-etape-code'];

            /** @var Intervenant $intervenant */
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$intervenant) return false;

            return $this->assertVisualisationMission($role, $intervenant);
        }

        return false;
    }



    /**
     * Exemple
     */
    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {

//        switch (true) {
//            case $entity instanceof VotreEntite:
//                switch ($privilege) {
//                    case Privileges::VOTRE_PRIVILEGE: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
//                        return $this->assertVotreAssertion($role, $entity);
//                }
//                break;
//        }

        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        /* @var $intervenant Intervenant */
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        if (!$intervenant) return false;

        // Si c'est bon alors on affine...
        switch ($action) {
            case 'index':
                return $this->assertVisualisationMission($role, $intervenant);
            break;
        }

        return true;
    }



    protected function assertVisualisationMission(Role $role, Intervenant $intervenant)
    {
        return $this->asserts([
            $role->hasPrivilege(Privileges::MISSION_VISUALISATION),
            $intervenant->getStatut()->getMission(),
        ]);
    }

}
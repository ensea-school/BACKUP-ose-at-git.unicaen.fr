<?php

namespace Agrement\Assertion;

use Agrement\Entity\Db\Agrement;
use Agrement\Entity\Db\TblAgrement;
use Agrement\Entity\Db\TypeAgrement;
use Application\Acl\Role;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Resource\WorkflowResource;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of AgrementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementAssertion extends AbstractAssertion
{


    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof TblAgrement:
                switch ($privilege) {
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION:
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION:
                        return $this->assertTblAgrementSaisie($role, $entity);
                }
            break;
            case $entity instanceof Agrement:
                switch ($privilege) {
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION:
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION:
                        return $this->assertAgrementSaisie($role, $entity);
                }
            break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION:
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION:
                        return $this->assertStructureSaisie($role, $entity);
                }
            break;
        }

        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role        = $this->getRole();
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $typeAgrement = $this->getMvcEvent()->getParam('typeAgrement');
        /* @var $typeAgrement TypeAgrement */

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch ($action) {
            case 'lister':
            case 'voir':
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeVisualisation());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
            break;

            case 'ajouter':
            case 'modifier':
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeEdition());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
            break;

            case 'index':
            case 'saisir-lot':
                if ($role->getIntervenant()) return false; // un intervenant ne peut pas voir ça...
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeVisualisation());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
            break;

            case 'supprimer':
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeSuppression());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
            break;
        }

        return true;
    }



    protected function assertPage(array $page)
    {
        $role        = $this->getRole();
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        $wfEtape   = null;
        $privilege = null;
        if (false !== strpos($page['route'], 'conseil-restreint')) {
            $wfEtape   = WfEtape::CODE_CONSEIL_RESTREINT;
            $privilege = Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION;
        } elseif (false !== strpos($page['route'], 'conseil-academique')) {
            $wfEtape   = WfEtape::CODE_CONSEIL_ACADEMIQUE;
            $privilege = Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION;
        }

        if (!$role instanceof Role) return false;

        if ($role->getIntervenant() && str_starts_with($page['route'], 'gestion/')) {
            return false;
        }

        if ($privilege && !$role->hasPrivilege($privilege)) return false;
        if ($wfEtape && $intervenant && !$this->isAllowed(WorkflowResource::create($wfEtape, $intervenant))) return false;

        return true;
    }



    protected function assertTblAgrementSaisie(Role $role, TblAgrement $entity)
    {
        /* Si c'est pour agréer et que le workflow l'interdit alors non! */
        if (!$entity->getAgrement() && !$this->isAllowed($entity->getResourceWorkflow())) {
            return false;
        }

        if ($structure = $entity->getStructure()) {
            return $this->assertStructureSaisie($role, $structure);
        }

        return true;
    }



    protected function assertTypeAgrementVisualisation(TypeAgrement $typeAgrement, Intervenant $intervenant)
    {
        return $this->isAllowed(WorkflowResource::create($typeAgrement->getCode(), $intervenant));
    }



    protected function assertAgrementSaisie(Role $role, Agrement $entity)
    {
        if (!$this->isAllowed($entity->getResourceWorkflow())) {
            return false;
        }

        if ($structure = $entity->getStructure()) {
            return $this->assertStructureSaisie($role, $structure);
        }

        return true;
    }



    protected function assertStructureSaisie(Role $role, Structure $entity)
    {
        if ($roleStructure = $role->getStructure()) {
            if (!$entity->inStructure($roleStructure)) return false; // pas d'édition pour les copains
        }

        return true;
    }

}
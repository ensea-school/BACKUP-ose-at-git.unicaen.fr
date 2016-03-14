<?php

namespace Application\Assertion;

use Application\Entity\Db\Agrement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TblAgrement;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Resource\WorkflowResource;
use Application\Service\Traits\TblAgrementServiceAwareTrait;
use Application\Service\Traits\TypeAgrementAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Application\Acl\Role;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of AgrementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementAssertion extends AbstractAssertion
{
    use TypeAgrementAwareTrait;
    use TblAgrementServiceAwareTrait;



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
        if ($privilege && !$this->isAllowed(Privileges::getResourceId($privilege))) return false;

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
        if ($privilege && !$this->isAllowed(Privileges::getResourceId($privilege))) return false;

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
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        $wfEtape = null;
        $privilege = null;
        if (false !== strpos($page['route'], 'conseil-restreint')) {
            $wfEtape   = WfEtape::CODE_CONSEIL_RESTREINT;
            $privilege = Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION;
        } elseif (false !== strpos($page['route'], 'conseil-academique')) {
            $wfEtape   = WfEtape::CODE_CONSEIL_ACADEMIQUE;
            $privilege = Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION;
        }

        if ($privilege && !$this->isAllowed(Privileges::getResourceId($privilege))) return false;
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
            if ($roleStructure != $entity) return false; // pas d'édition pour les copains
        }

        return true;
    }

}
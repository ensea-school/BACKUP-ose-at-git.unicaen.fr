<?php

namespace OffreFormation\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\TypeInterventionStructure;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of OffreDeFormationAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        $role = $this->getRole();
        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof TypeInterventionStructure:
                switch ($privilege) {
                    case Privileges::TYPE_INTERVENTION_EDITION:
                        return $this->assertTypeInterventionStructureSaisie($role, $entity);
                }
            break;
        }

        return true;
    }



    protected function assertTypeInterventionStructureSaisie(Role $role, TypeInterventionStructure $tis)
    {
        return $this->assertStructureSaisie($role, $tis->getStructure());
    }



    protected function assertStructureSaisie(Role $role, Structure $structure)
    {
        if ($rs = $role->getStructure()) {
            return $structure->inStructure($rs);
        }

        return true;
    }
}
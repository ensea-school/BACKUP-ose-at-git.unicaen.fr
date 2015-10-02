<?php

namespace Application\Assertion;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Source;
use Application\Entity\Db\Structure;
use Zend\Permissions\Acl\Acl;
use Application\Acl\Role;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Application\Entity\Db\Privilege;


/**
 * Description of OffreDeFormationAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class OffreDeFormationAssertion extends AbstractAssertion
{
    protected function assertEntity(Acl $acl, RoleInterface $role = null, ResourceInterface $entity = null, $privilege = null)
    {
        if (!$role instanceof Role) return false;

        /* @var $role \Application\Entity\Db\Role */
        if (! $this->acl->isAllowed($role,'privilege/'.$privilege)) return false;

        if ($entity instanceof ElementPedagogique) {
            switch ($privilege) {
                case Privilege::ODF_ELEMENT_EDITION:
                    return $this->assertElementPedagogiqueSaisie($role, $entity);
            }
        }elseif ($entity instanceof Etape) {
            switch ($privilege) {
                case Privilege::ODF_ETAPE_EDITION:
                    return $this->assertEtapeSaisie($role, $entity);
            }
        }elseif($entity instanceof Structure){
            switch ($privilege) {
                case Privilege::ODF_ETAPE_EDITION:
                case Privilege::ODF_ELEMENT_EDITION:
                    return $this->assertStructureSaisie($role, $entity);
            }
        }
        return true;
    }



    protected function assertElementPedagogiqueSaisie(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->assertStructureSaisie($role, $elementPedagogique->getStructure())
        && $this->assertSourceSaisie($elementPedagogique->getSource());
    }



    protected function assertEtapeSaisie(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
            && $this->assertSourceSaisie($etape->getSource());
    }



    protected function assertStructureSaisie(Role $role, Structure $structure)
    {
        if ($rs = $role->getStructure()) {
            return $rs === $structure;
        }
        return true;
    }



    protected function assertSourceSaisie(Source $source)
    {
        return $source->isOse();
    }
}
<?php

namespace OffreFormation\Assertion;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\TypeInterventionStructure;


/**
 * Description of OffreDeFormationAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof TypeInterventionStructure:
                switch ($privilege) {
                    case Privileges::TYPE_INTERVENTION_EDITION:
                        return $this->assertTypeInterventionStructureSaisie($entity);
                }
            break;
        }

        return true;
    }



    protected function assertTypeInterventionStructureSaisie(TypeInterventionStructure $tis): bool
    {
        return $this->assertStructureSaisie($tis->getStructure());
    }



    protected function assertStructureSaisie(Structure $structure): bool
    {
        if ($rs = $this->getServiceContext()->getStructure()) {
            return $structure->inStructure($rs);
        }

        return true;
    }
}
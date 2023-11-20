<?php

namespace Paiement\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\CentreCoutStructure;
use Referentiel\Service\ServiceReferentielServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;

/**
 * Description of PaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCoutAssertion extends AbstractAssertion
{
    use TypeValidationServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use WorkflowServiceAwareTrait;


    /**
     * @param ResourceInterface $entity
     * @param string $privilege
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
            case $entity instanceof CentreCoutStructure:
                switch ($privilege) {
                    case Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION:
                        return $this->assertCentreCoutStructure($role, $entity);
                }
                break;
        }

        return true;
    }



    public function assertCentreCoutStructure(Role $role, CentreCoutStructure $centreCoutStructure): bool
    {
        if ($role->getStructure() && $centreCoutStructure->getStructure()) {
            return $centreCoutStructure->getStructure()->inStructure($role->getStructure());
        }

        return true;
    }

}
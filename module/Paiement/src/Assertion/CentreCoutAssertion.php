<?php

namespace Paiement\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\CentreCoutStructure;

/**
 * Description of PaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCoutAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    /**
     * @param ResourceInterface $entity
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof CentreCoutStructure:
                switch ($privilege) {
                    case Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION:
                        return $this->assertCentreCoutStructure($entity);
                }
                break;
        }

        return true;
    }



    public function assertCentreCoutStructure(CentreCoutStructure $centreCoutStructure): bool
    {
        if ($this->getServiceContext()->getStructure() && $centreCoutStructure->getStructure()) {
            return $centreCoutStructure->getStructure()->inStructure($this->getServiceContext()->getStructure());
        }

        return true;
    }

}
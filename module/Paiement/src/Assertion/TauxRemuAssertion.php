<?php

namespace Paiement\Assertion;

use Application\Entity\Db\Agrement;
use Application\Provider\Privileges;
use Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\TauxRemu;
use Utilisateur\Acl\Role;


/**
 * Description of TauxRemuAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxRemuAssertion extends AbstractAssertion
{

    /**
     * Exemple
     */
    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case
                $entity instanceof TauxRemu:
                switch ($privilege) {
                    case Privileges::TAUX_SUPPRESSION:
                        return $this->assertTauxRemuSuppression($entity);
                    break;
                    case Privileges::TAUX_EDITION:
                        return $this->assertTauxRemuEdition($entity);
                    break;
                }
            break;
        }

        return true;
    }



    /**
     * @param Role     $role
     * @param TauxRemu $entity
     *
     * @return bool
     */
    private function assertTauxRemuEdition(TauxRemu $entity): bool
    {
        return !$entity->isDefaut();
    }



    /**
     * @param Role     $role
     * @param TauxRemu $entity
     *
     * @return bool
     */
    private function assertTauxRemuSuppression(TauxRemu $entity): bool
    {
        return !$entity->isDefaut() & !$entity->hasChildren();
    }

}


<?php

namespace Intervenant\Assertion;

use Application\Provider\Privileges;
use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Statut;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Utilisateur\Acl\Role;


/**
 * Description of StatutAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class StatutAssertion extends AbstractAssertion
{

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Statut:
                switch ($privilege) {
                    case Privileges::INTERVENANT_STATUT_EDITION:
                        return $this->assertStatutEdition($entity);
                }
            break;
        }
    }



    /* Vos autres tests */

    function assertStatutEdition(Statut $statut): bool
    {
        if ($statut->isAutres() || $statut->isNonAutorise()) {
            return false;
        }

        return true;
    }

}
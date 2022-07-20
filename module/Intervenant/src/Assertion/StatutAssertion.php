<?php

namespace Intervenant\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Statut;
use UnicaenAuth\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of StatutAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class StatutAssertion extends AbstractAssertion
{

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Statut:
                switch ($privilege) {
                    case Privileges::INTERVENANT_STATUT_EDITION: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertStatutEdition($entity);
                }
            break;
        }
    }



    /* Vos autres tests */

    function assertStatutEdition(Statut $statut)
    {
        if ($statut->isAutres() || $statut->isNonAutorise()) {
            return false;
        }

        return true;
    }

}
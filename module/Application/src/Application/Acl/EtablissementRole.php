<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "composante".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementRole extends NamedRole implements PersonnelAwareInterface
{
    use PersonnelAwareTrait;

    const ROLE_ID = 'etablissement';

    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Établissement', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

}

class SuperviseurEtablissementRole extends EtablissementRole
{
    const ROLE_ID = 'superviseur-etablissement';

    public function __construct($id = self::ROLE_ID, $parent = EtablissementRole::ROLE_ID, $name = 'Superviseur établissement', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}
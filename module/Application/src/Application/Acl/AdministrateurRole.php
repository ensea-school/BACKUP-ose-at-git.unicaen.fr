<?php

namespace Application\Acl;

use Application\Interfaces\StructureAwareInterface;
use Application\Traits\StructureAwareTrait;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "administrateur".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AdministrateurRole extends Role implements StructureAwareInterface, PersonnelAwareInterface
{
    use StructureAwareTrait;
    use PersonnelAwareTrait;

    const ROLE_ID = 'administrateur';

    public function __construct($id = self::ROLE_ID, $parent = Role::ROLE_ID, $name = 'Administrateur', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleName() . (($s = $this->getStructure()) ? " ($s)" : null);
    }
}
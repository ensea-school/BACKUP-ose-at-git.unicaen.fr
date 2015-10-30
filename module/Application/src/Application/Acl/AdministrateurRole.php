<?php

namespace Application\Acl;

use Application\Entity\Db\Interfaces\StructureAwareInterface;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Interfaces\PersonnelAwareInterface;
use Application\Entity\Db\Traits\PersonnelAwareTrait;

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
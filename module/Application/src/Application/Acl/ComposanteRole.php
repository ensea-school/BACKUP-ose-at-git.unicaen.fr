<?php

namespace Application\Acl;

use Application\Interfaces\StructureAwareInterface;
use Application\Traits\StructureAwareTrait;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "composante".
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ComposanteRole extends Role implements StructureAwareInterface, PersonnelAwareInterface
{
    use StructureAwareTrait;
    use PersonnelAwareTrait;

    const ROLE_ID = 'composante';

    public function __construct($id = self::ROLE_ID, $parent = Role::ROLE_ID, $name = 'Composante', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        if ($structure = $this->getStructure()){
            return static::ROLE_ID.'-'.$structure->getSourceCode();
        }else{
            return static::ROLE_ID;
        }
    }

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s (%s)", $this->getRoleName(), $this->getStructure());
    }
}

class DirecteurComposanteRole extends ComposanteRole
{
    const ROLE_ID = 'directeur-composante';

    public function __construct($id = self::ROLE_ID, $parent = ComposanteRole::ROLE_ID, $name = 'Directeur de composante', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}

class GestionnaireComposanteRole extends ComposanteRole
{
    const ROLE_ID = 'gestionnaire-composante';

    public function __construct($id = self::ROLE_ID, $parent = ComposanteRole::ROLE_ID, $name = 'Gestionnaire de composante', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}

class ResponsableComposanteRole extends ComposanteRole
{
    const ROLE_ID = 'responsable-composante';

    public function __construct($id = self::ROLE_ID, $parent = ComposanteRole::ROLE_ID, $name = 'Responsable de composante', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}

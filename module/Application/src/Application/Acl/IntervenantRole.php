<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Interfaces\StructureAwareInterface;
use Application\Interfaces\IntervenantAwareInterface;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;

/**
 * Description of IntervenantRole
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantRole extends Role implements StructureAwareInterface, IntervenantAwareInterface
{
    use StructureAwareTrait;
    use IntervenantAwareTrait;

    const ROLE_ID = "intervenant";

    public function __construct($id = self::ROLE_ID, $parent = Role::ROLE_ID, $name = 'Intervenant', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}



class IntervenantPermanentRole extends IntervenantRole
{
    const ROLE_ID = "intervenant-permanent";

    public function __construct($id = self::ROLE_ID, $parent = IntervenantRole::ROLE_ID, $name = "Intervenant permanent", $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}



class IntervenantExterieurRole extends IntervenantRole
{
    const ROLE_ID = "intervenant-exterieur";
    protected $parent = IntervenantRole::ROLE_ID;

    public function __construct($id = self::ROLE_ID, $parent = IntervenantRole::ROLE_ID, $name = "Intervenant vacataire", $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}
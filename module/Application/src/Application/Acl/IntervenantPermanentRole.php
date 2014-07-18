<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;

/**
 * Rôle correspondant à un intervenant permanent.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantPermanentRole extends IntervenantRole
{
    const ROLE_ID = "intervenant-permanent";
    
    /**
     * Constructeur.
     * 
     * @param string|null               $id
     * @param RoleInterface|string|null $parent
     * @param string                    $name
     * @param string                    $description
     * @param bool                      $selectable
     */
    public function __construct($id = null, $parent = null, $name = null, $description = null, $selectable = true)
    {
        NamedRole::__construct(
                $id = static::ROLE_ID, 
                $parent = IntervenantRole::ROLE_ID, 
                $name = "Intervenant permanent", 
                $description, 
                $selectable);
    }
}
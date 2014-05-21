<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;

/**
 * Description of IntervenantExterieurRole
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantExterieurRole extends IntervenantRole
{
    const ROLE_ID = "intervenant-exterieur";
    
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
                $name = "Intervenant (vacataire)", 
                $description, 
                $selectable);
    }
}
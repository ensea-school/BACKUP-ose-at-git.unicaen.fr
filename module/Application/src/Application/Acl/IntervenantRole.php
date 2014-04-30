<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;

/**
 * Description of IntervenantRole
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantRole extends NamedRole
{
    const ROLE_ID = "intervenant";
    
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
        parent::__construct($id = self::ROLE_ID, $parent = 'user', $name = "Intervenant", $description, $selectable);
    }
}
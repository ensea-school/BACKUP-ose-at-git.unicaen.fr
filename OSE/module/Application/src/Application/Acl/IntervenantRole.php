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
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;
    
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
                $parent = 'user', 
                $name = "Intervenant", 
                $description, 
                $selectable);
    }
    
    /**
     * 
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Acl\IntervenantRole
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        return $this;
    }
}
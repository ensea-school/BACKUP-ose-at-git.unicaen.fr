<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Entity\Db\Role as DbRole;

/**
 * Rôle père de tous les rôles "administrateur".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends NamedRole
{
    use \Application\Traits\StructureAwareTrait,
        \Application\Traits\PersonnelAwareTrait,
        \Application\Traits\IntervenantAwareTrait;

    const ROLE_ID = 'role';

    /**
     * Rôle en BDD
     *
     * @var DbRole
     */
    protected $dbRole;



    /**
     * Détermine si le périmètre du rôle courant est de niveau intervenant
     *
     * @return bool
     */
    public function isPerimetreIntervenant()
    {
        return $this->getIntervenant() ? true : false;
    }



    /**
     * Détermine si le périmètre du rôle courant est de niveau composante
     *
     * @return bool
     */
    public function isPerimetreComposante()
    {
        return $this->getStructure() ? true : false;
    }



    /**
     * Détermine si le périmètre du rôle courant est de niveau établissement
     *
     * @return bool
     */
    public function isPerimetreEtablissement()
    {
        return !$this->getStructure() && !$this->getIntervenant();
    }



    /**
     * Détermine si le rôle courant possède un privilège ou non
     *
     * @param $privilege
     *
     * @return bool
     */
    public function hasPrivilege($privilege)
    {
        if ($this->dbRole) {
            return $this->dbRole->hasPrivilege($privilege);
        } else {
            return false;
        }
    }



    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Rôle', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

}
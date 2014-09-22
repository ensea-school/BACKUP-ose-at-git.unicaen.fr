<?php

namespace Application\Traits;

use Application\Entity\Db\TypeRole;

/**
 * Description of TypeRoleAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait TypeRoleAwareTrait
{
    /**
     * @var TypeRole
     */
    protected $typeRole;

    /**
     * Spécifie le type de rôle concerné.
     *
     * @param TypeRole $typeRole Type de rôle concerné
     */
    public function setTypeRole(TypeRole $typeRole = null)
    {
        $this->typeRole = $typeRole;

        return $this;
    }

    /**
     * Retourne le type de rôle concerné.
     *
     * @return TypeRole
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }
}
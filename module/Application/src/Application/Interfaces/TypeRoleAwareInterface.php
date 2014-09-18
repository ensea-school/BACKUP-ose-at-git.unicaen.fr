<?php

namespace Application\Interfaces;

use Application\Entity\Db\TypeRole;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface TypeRoleAwareInterface
{

    /**
     * Spécifie le type de rôle concerné.
     *
     * @param TypeRole $typeRole Type de rôle concernée
     */
    public function setTypeRole(TypeRole $typeRole = null);

    /**
     * Retourne le type de rôle concerné.
     *
     * @return TypeRole
     */
    public function getTypeRole();
}
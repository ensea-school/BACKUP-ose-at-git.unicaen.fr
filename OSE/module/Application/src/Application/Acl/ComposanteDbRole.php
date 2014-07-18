<?php

namespace Application\Acl;

/**
 * Rôle correspondant à une responsabilité issue de la bdd (entité Role).
 *
 * @see \Application\Entity\Db\Role
 * @see \Application\Entity\Db\TypeRole
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ComposanteDbRole extends DbRole
{
    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s (%s)", "Composante", $this->getStructure());
    }
}
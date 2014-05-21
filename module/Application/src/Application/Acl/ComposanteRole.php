<?php

namespace Application\Acl;

/**
 * Description of IntervenantRole
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ComposanteRole extends DbRole
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
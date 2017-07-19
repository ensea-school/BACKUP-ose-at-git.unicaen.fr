<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypePoste;

/**
 * Description of TypePosteAwareInterface
 *
 * @author UnicaenCode
 */
interface TypePosteAwareInterface
{
    /**
     * @param TypePoste $typePoste
     * @return self
     */
    public function setTypePoste( TypePoste $typePoste = null );



    /**
     * @return TypePoste
     */
    public function getTypePoste();
}
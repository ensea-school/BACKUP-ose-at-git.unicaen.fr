<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of IntervenantExterieurAwareInterface
 *
 * @author UnicaenCode
 */
interface IntervenantExterieurAwareInterface
{
    /**
     * @param IntervenantExterieur $intervenantExterieur
     * @return self
     */
    public function setIntervenantExterieur( IntervenantExterieur $intervenantExterieur = null );



    /**
     * @return IntervenantExterieur
     */
    public function getIntervenantExterieur();
}
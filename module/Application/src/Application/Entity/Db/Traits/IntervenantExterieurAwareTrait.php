<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of IntervenantExterieurAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantExterieurAwareTrait
{
    /**
     * @var IntervenantExterieur
     */
    private $intervenantExterieur;





    /**
     * @param IntervenantExterieur $intervenantExterieur
     * @return self
     */
    public function setIntervenantExterieur( IntervenantExterieur $intervenantExterieur = null )
    {
        $this->intervenantExterieur = $intervenantExterieur;
        return $this;
    }



    /**
     * @return IntervenantExterieur
     */
    public function getIntervenantExterieur()
    {
        return $this->intervenantExterieur;
    }
}
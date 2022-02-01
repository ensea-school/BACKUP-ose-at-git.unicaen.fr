<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Noeud;

/**
 * Description of NoeudAwareTrait
 *
 * @author UnicaenCode
 */
trait NoeudAwareTrait
{
    protected ?Noeud $noeud = null;



    /**
     * @param Noeud $noeud
     *
     * @return self
     */
    public function setNoeud( ?Noeud $noeud )
    {
        $this->noeud = $noeud;

        return $this;
    }



    public function getNoeud(): ?Noeud
    {
        return $this->noeud;
    }
}
<?php

namespace Application\Entity\Chargens\Traits;

use Application\Entity\Chargens\Noeud;

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
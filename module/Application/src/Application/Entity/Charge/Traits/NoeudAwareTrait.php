<?php

namespace Application\Entity\Charge\Traits;

use Application\Entity\Charge\Noeud;

/**
 * Description of NoeudAwareTrait
 *
 */
trait NoeudAwareTrait
{
    /**
     * @var Noeud
     */
    private $noeud;





    /**
     * @param Noeud $noeud
     * @return self
     */
    public function setNoeud( Noeud $noeud = null )
    {
        $this->noeud = $noeud;
        return $this;
    }



    /**
     * @return Noeud
     */
    public function getNoeud()
    {
        return $this->noeud;
    }
}
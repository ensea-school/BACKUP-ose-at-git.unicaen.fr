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
    /**
     * @var Noeud
     */
    private $noeud;



    /**
     * @param Noeud $noeud
     *
     * @return self
     */
    public function setNoeud(Noeud $noeud = null)
    {
        $this->noeud = $noeud;

        return $this;
    }



    public function getNoeud(): Noeud
    {
        return $this->noeud;
    }
}
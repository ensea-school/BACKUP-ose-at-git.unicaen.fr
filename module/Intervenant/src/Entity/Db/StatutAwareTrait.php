<?php

namespace Intervenant\Entity\Db;


/**
 * Description of StatutAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutAwareTrait
{
    protected ?Statut $statut = null;



    /**
     * @param Statut $statut
     *
     * @return self
     */
    public function setStatut( ?Statut $statut )
    {
        $this->statut = $statut;

        return $this;
    }



    public function getStatut(): ?Statut
    {
        return $this->statut;
    }
}
<?php

namespace Intervenant\Entity\Db;


/**
 * Description of StatutAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutAwareTrait
{
    private ?Statut $statut;



    public function setStatut(?Statut $statut)
    {
        $this->statut = $statut;

        return $this;
    }



    public function getStatut(): ?Statut
    {
        return $this->statut;
    }
}
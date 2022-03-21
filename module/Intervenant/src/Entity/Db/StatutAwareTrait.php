<?php

namespace Intervenant\Entity\Db;


/**
 * Description of StatutAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutAwareTrait
{
    /**
     * @var Statut|null
     */
    protected ?Statut $statut = null;



    /**
     * @param Statut|null $statut
     *
     * @return self
     */
    public function setStatut(?Statut $statut)
    {
        $this->statut = $statut;
        

        return $this;
    }



    /**
     * @return Statut|null
     */
    public function getStatut(): ?Statut
    {
        return $this->statut;
    }
}
<?php

namespace Service\Entity\Db;

use Intervenant\Entity\Db\IntervenantAwareTrait;
use Utilisateur\Entity\Db\Utilisateur;

/**
 * HistoIntervenantService
 */
class HistoIntervenantService
{
    use IntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;

    private bool         $referentiel       = false;

    private ?\DateTime   $histoModification = null;

    private ?int         $id                = null;

    private ?Utilisateur $histoModificateur = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setReferentiel(bool $referentiel): HistoIntervenantService
    {
        $this->referentiel = $referentiel;

        return $this;
    }



    public function getReferentiel(): bool
    {
        return $this->referentiel;
    }



    public function setHistoModification(?\DateTime $histoModification): HistoIntervenantService
    {
        $this->histoModification = $histoModification;

        return $this;
    }



    public function getHistoModification(): ?\DateTime
    {
        return $this->histoModification;
    }



    public function setHistoModificateur(?Utilisateur $histoModificateur = null): HistoIntervenantService
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }



    public function getHistoModificateur(): ?Utilisateur
    {
        return $this->histoModificateur;
    }
}


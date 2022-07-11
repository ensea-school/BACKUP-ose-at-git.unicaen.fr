<?php

namespace Service\Entity\Db;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Service\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Utilisateur;

/**
 * HistoIntervenantService
 */
class HistoIntervenantService
{
    use IntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;

    private bool         $referentiel = false;

    private ?\DateTime   $histoModification;

    private ?int         $id;

    private ?Utilisateur $histoModificateur;



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


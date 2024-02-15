<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleIntervenant;
use Intervenant\Entity\Db\Intervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

class FormuleResultatIntervenant extends FormuleIntervenant
{
    protected Intervenant $intervenant;

    protected float $total = 0.0;

    protected float $solde = 0.0;

    protected float $sousService = 0.0;



    public function init(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): self
    {
        $this->intervenant = $intervenant;
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getTotal(): float
    {
        return $this->total;
    }



    public function getSolde(): float
    {
        return $this->solde;
    }



    public function getSousService(): float
    {
        return $this->sousService;
    }
}
<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleIntervenant;
use Intervenant\Entity\Db\Intervenant;

class FormuleResultatIntervenant extends FormuleIntervenant
{
    protected Intervenant $intervenant;

    protected float $total = 0.0;

    protected float $solde = 0.0;

    protected float $sousService = 0.0;



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
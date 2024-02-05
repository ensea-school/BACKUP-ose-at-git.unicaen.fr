<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleIntervenant;
use Intervenant\Entity\Db\Intervenant;

class FormuleResultatIntervenant extends FormuleIntervenant
{
    protected float $solde = 0.0;

    protected float $sousService = 0.0;

    protected Intervenant $intervenant;



    public function getSolde(): float
    {
        return $this->solde;
    }



    public function getSousService(): float
    {
        return $this->sousService;
    }


    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }

}
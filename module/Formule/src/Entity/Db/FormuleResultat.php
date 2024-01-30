<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleIntervenant;
use Intervenant\Entity\Db\Intervenant;

class FormuleResultat extends FormuleIntervenant
{
    protected float $solde = 0.0;

    protected Intervenant $intervenant;




    public function getSolde(): float
    {
        return $this->solde;
    }



    public function getHeuresCompl(): float
    {
        return $this->getHeuresComplFi() + $this->getHeuresComplFa() + $this->getHeuresComplFc() + $this->getHeuresComplFcMajorees() + $this->getHeuresComplReferentiel();
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }

}

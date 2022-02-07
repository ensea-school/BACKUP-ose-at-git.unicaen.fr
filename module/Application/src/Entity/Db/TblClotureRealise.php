<?php

namespace Application\Entity\Db;

class TblClotureRealise
{
    private int         $id;

    private bool        $estCloture = false;

    private bool        $hasCloture = false;

    private Intervenant $intervenant;

    private Annee       $annee;



    public function estCloture(): bool
    {
        return $this->estCloture;
    }



    public function hasCloture(): bool
    {
        return $this->hasCloture;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getAnnee(): Annee
    {
        return $this->annee;
    }
}


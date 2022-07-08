<?php

namespace Application\Entity\Db;

class TblClotureRealise
{
    private int         $id;

    private Annee       $annee;

    private Intervenant $intervenant;

    private bool        $actif   = false;

    private bool        $cloture = false;



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return Annee
     */
    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    /**
     * @return bool
     */
    public function getActif(): bool
    {
        return $this->actif;
    }



    /**
     * @return bool
     */
    public function getCloture(): bool
    {
        return $this->cloture;
    }

}


<?php

namespace Application\Entity\Db;

use OffreFormation\Entity\Db\TypeHeures;

trait FormuleResultatTypesHeuresTrait
{
    private float $heuresServiceFi = 0.0;

    private float $heuresServiceFa = 0.0;

    private float $heuresServiceFc = 0.0;

    private float $heuresServiceReferentiel = 0.0;

    private float $heuresComplFi = 0.0;

    private float $heuresComplFa = 0.0;

    private float $heuresComplFc = 0.0;

    private float $heuresComplFcMajorees = 0.0;

    private float $heuresComplReferentiel = 0.0;

    private float $total = 0.0;



    public function getHeuresServiceFi(): float
    {
        return $this->heuresServiceFi;
    }



    public function getHeuresServiceFa(): float
    {
        return $this->heuresServiceFa;
    }



    public function getHeuresServiceFc(): float
    {
        return $this->heuresServiceFc;
    }



    public function getHeuresServiceReferentiel(): float
    {
        return $this->heuresServiceReferentiel;
    }



    public function getHeuresComplFi(): float
    {
        return $this->heuresComplFi;
    }



    public function getHeuresComplFa(): float
    {
        return $this->heuresComplFa;
    }



    public function getHeuresComplFc(): float
    {
        return $this->heuresComplFc;
    }



    public function getHeuresComplFcMajorees(): float
    {
        return $this->heuresComplFcMajorees;
    }



    public function getHeuresComplReferentiel(): float
    {
        return $this->heuresComplReferentiel;
    }



    public function getTotal(): float
    {
        return $this->total;
    }



    public function getHeuresService(TypeHeures $typeHeures = null): float
    {
        if (null === $typeHeures) {
            return $this->getHeuresServiceFi()
                + $this->getHeuresServiceFa()
                + $this->getHeuresServiceFc()
                + $this->getHeuresServiceReferentiel();
        }

        switch ($typeHeures->getCode()) {
            case TypeHeures::FI:
                return $this->getHeuresServiceFi();
            case TypeHeures::FA:
                return $this->getHeuresServiceFa();
            case TypeHeures::FC:
                return $this->getHeuresServiceFc();
            case TypeHeures::ENSEIGNEMENT:
                return $this->getHeuresServiceFi() + $this->getHeuresServiceFa() + $this->getHeuresServiceFc();
            case TypeHeures::REFERENTIEL:
                return $this->getHeuresServiceReferentiel();
            case TypeHeures::MISSION:
                return 0;
        }
        throw new \RuntimeException('Type d\'heures inconnu ou non pris en charge');
    }



    public function getHeuresCompl(TypeHeures $typeHeures): float
    {
        switch ($typeHeures->getCode()) {
            case TypeHeures::FI:
                return $this->getHeuresComplFi();
            case TypeHeures::FA:
                return $this->getHeuresComplFa();
            case TypeHeures::FC:
                return $this->getHeuresComplFc();
            case TypeHeures::FC_MAJOREES:
                return $this->getHeuresComplFcMajorees();
            case TypeHeures::ENSEIGNEMENT:
                return $this->getHeuresComplFi() + $this->getHeuresComplFa() + $this->getHeuresComplFc() + $this->getHeuresComplFcMajorees();
            case TypeHeures::REFERENTIEL:
                return $this->getHeuresComplReferentiel();
            case TypeHeures::MISSION:
                return $this->getHeuresMission();
        }
        throw new \RuntimeException('Type d\'heures inconnu ou non pris en charge');
    }

}
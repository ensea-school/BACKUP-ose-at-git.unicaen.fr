<?php

namespace Application\Entity\Db;

use OffreFormation\Entity\Db\TypeHeures;

trait FormuleResultatTypesHeuresTrait
{
    /**
     * @var float
     */
    private $heuresServiceFi = 0.0;

    /**
     * @var float
     */
    private $heuresServiceFa = 0.0;

    /**
     * @var float
     */
    private $heuresServiceFc = 0.0;

    /**
     * @var float
     */
    private $heuresServiceReferentiel = 0.0;

    /**
     * @var float
     */
    private $heuresComplFi = 0.0;

    /**
     * @var float
     */
    private $heuresComplFa = 0.0;

    /**
     * @var float
     */
    private $heuresComplFc = 0.0;

    /**
     * @var float
     */
    private $heuresComplFcMajorees = 0.0;

    /**
     * @var float
     */
    private $heuresComplReferentiel = 0.0;

    /**
     *
     * @var float
     */
    private $total = 0.0;



    /**
     * Get heuresServiceFi
     *
     * @return float
     */
    public function getHeuresServiceFi()
    {
        return $this->heuresServiceFi;
    }



    /**
     * Get heuresServiceFa
     *
     * @return float
     */
    public function getHeuresServiceFa()
    {
        return $this->heuresServiceFa;
    }



    /**
     * Get heuresServiceFc
     *
     * @return float
     */
    public function getHeuresServiceFc()
    {
        return $this->heuresServiceFc;
    }



    /**
     * Get heuresServiceReferentiel
     *
     * @return float
     */
    public function getHeuresServiceReferentiel()
    {
        return $this->heuresServiceReferentiel;
    }



    /**
     * Get heuresComplFi
     *
     * @return float
     */
    public function getHeuresComplFi()
    {
        return $this->heuresComplFi;
    }



    /**
     * Get heuresComplFa
     *
     * @return float
     */
    public function getHeuresComplFa()
    {
        return $this->heuresComplFa;
    }



    /**
     * Get heuresComplFc
     *
     * @return float
     */
    public function getHeuresComplFc()
    {
        return $this->heuresComplFc;
    }



    /**
     * Get heuresComplFcMajorees
     *
     * @return float
     */
    public function getHeuresComplFcMajorees()
    {
        return $this->heuresComplFcMajorees;
    }



    /**
     * Get heuresComplReferentiel
     *
     * @return float
     */
    public function getHeuresComplReferentiel()
    {
        return $this->heuresComplReferentiel;
    }



    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }



    /**
     *
     * @param TypeHeures|null $typeHeures
     *
     * @return float
     * @throws \RuntimeException
     */
    public function getHeuresService(TypeHeures $typeHeures = null)
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
            case TypeHeures::REFERENTIEL:
                return $this->getHeuresServiceReferentiel();
        }
        throw new \RuntimeException('Type d\'heures inconnu ou non pris en charge');
    }



    /**
     *
     * @param TypeHeures $typeHeures
     *
     * @return float
     * @throws \RuntimeException
     */
    public function getHeuresCompl(TypeHeures $typeHeures)
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
            case TypeHeures::REFERENTIEL:
                return $this->getHeuresComplReferentiel();
        }
        throw new \RuntimeException('Type d\'heures inconnu ou non pris en charge');
    }

}
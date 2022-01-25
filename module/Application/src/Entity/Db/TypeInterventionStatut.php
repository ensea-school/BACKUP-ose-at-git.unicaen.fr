<?php

namespace Application\Entity\Db;

/**
 * TypeInterventionStatut
 */
class TypeInterventionStatut
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $typeIntervention;

    /**
     * @var integer
     */
    protected $statutIntervenant;

    /**
     * @var float
     */
    protected $tauxHETDService;

    /**
     * @var float
     */
    protected $tauxHETDComplementaire;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set typeIntervention
     *
     * @param integer $typeIntervention
     *
     * @return TypeInterventionStatut
     */
    public function setTypeIntervention($typeIntervention)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    /**
     * Get typeIntevention
     *
     * @return integer
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }



    /**
     * Set statutIntervenant
     *
     * @param integer $statutIntervenant
     *
     * @return Statut
     */
    public function setStatutIntervenant($statutIntervenant)
    {
        $this->statutIntervenant = $statutIntervenant;

        return $this;
    }



    /**
     * Get statutIntervenant
     *
     * @return integer
     */
    public function getStatutIntervenant()
    {
        return $this->statutIntervenant;
    }



    /**
     * Set tauxHetdService
     *
     * @param float $tauxHETDService
     *
     * @return TypeInterventionStatut
     */
    public function setTauxHETDService($tauxHETDService)
    {
        $this->tauxHETDService = $tauxHETDService;

        return $this;
    }



    /**
     * Get tauxHETDService
     *
     * @return float
     */
    public function getTauxHETDService()
    {
        return $this->tauxHETDService;
    }



    /**
     * Set tauxHETDComplementaire
     *
     * @param float $tauxHETDComplementaire
     *
     * @return TypeInterventionStatut
     */
    public function setTauxHETDComplementaire($tauxHETDComplementaire)
    {
        $this->tauxHETDComplementaire = $tauxHETDComplementaire;

        return $this;
    }



    /**
     * Get tauxHETDComplementaire
     *
     * @return float
     */
    public function getTauxHETDComplementaire()
    {
        return $this->tauxHETDComplementaire;
    }

}
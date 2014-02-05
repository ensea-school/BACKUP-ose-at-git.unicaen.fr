<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * VolumeHoraire
 */
class VolumeHoraire
{
    /**
     * @var string
     */
    private $aPayer;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $histoCreateur;

    /**
     * @var \DateTime
     */
    private $histoDebut;

    /**
     * @var integer
     */
    private $histoDestructeur;

    /**
     * @var \DateTime
     */
    private $histoFin;

    /**
     * @var integer
     */
    private $histoModificateur;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;

    /**
     * @var \Application\Entity\Db\Periode
     */
    private $periode;

    /**
     * @var \Application\Entity\Db\MotifNonPaiement
     */
    private $motifNonPaiement;

    /**
     * @var \Application\Entity\Db\TypeIntervention
     */
    private $typeIntervention;


    /**
     * Set aPayer
     *
     * @param string $aPayer
     * @return VolumeHoraire
     */
    public function setAPayer($aPayer)
    {
        $this->aPayer = $aPayer;

        return $this;
    }

    /**
     * Get aPayer
     *
     * @return string 
     */
    public function getAPayer()
    {
        return $this->aPayer;
    }

    /**
     * Set heures
     *
     * @param float $heures
     * @return VolumeHoraire
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

    /**
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
    }

    /**
     * Set histoCreateur
     *
     * @param integer $histoCreateur
     * @return VolumeHoraire
     */
    public function setHistoCreateur($histoCreateur)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return integer 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set histoDebut
     *
     * @param \DateTime $histoDebut
     * @return VolumeHoraire
     */
    public function setHistoDebut($histoDebut)
    {
        $this->histoDebut = $histoDebut;

        return $this;
    }

    /**
     * Get histoDebut
     *
     * @return \DateTime 
     */
    public function getHistoDebut()
    {
        return $this->histoDebut;
    }

    /**
     * Set histoDestructeur
     *
     * @param integer $histoDestructeur
     * @return VolumeHoraire
     */
    public function setHistoDestructeur($histoDestructeur)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return integer 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoFin
     *
     * @param \DateTime $histoFin
     * @return VolumeHoraire
     */
    public function setHistoFin($histoFin)
    {
        $this->histoFin = $histoFin;

        return $this;
    }

    /**
     * Get histoFin
     *
     * @return \DateTime 
     */
    public function getHistoFin()
    {
        return $this->histoFin;
    }

    /**
     * Set histoModificateur
     *
     * @param integer $histoModificateur
     * @return VolumeHoraire
     */
    public function setHistoModificateur($histoModificateur)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return integer 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return VolumeHoraire
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }

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
     * Set service
     *
     * @param \Application\Entity\Db\Service $service
     * @return VolumeHoraire
     */
    public function setService(\Application\Entity\Db\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return VolumeHoraire
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set motifNonPaiement
     *
     * @param \Application\Entity\Db\MotifNonPaiement $motifNonPaiement
     * @return VolumeHoraire
     */
    public function setMotifNonPaiement(\Application\Entity\Db\MotifNonPaiement $motifNonPaiement = null)
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }

    /**
     * Get motifNonPaiement
     *
     * @return \Application\Entity\Db\MotifNonPaiement 
     */
    public function getMotifNonPaiement()
    {
        return $this->motifNonPaiement;
    }

    /**
     * Set typeIntervention
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     * @return VolumeHoraire
     */
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = null)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }

    /**
     * Get typeIntervention
     *
     * @return \Application\Entity\Db\TypeIntervention 
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }
}

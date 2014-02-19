<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * VolumeHoraire
 */
class VolumeHoraire implements HistoriqueAwareInterface
{
    /**
     * @var boolean
     */
    private $aPayer;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;

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
     * @param boolean $aPayer
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
     * @return boolean 
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return VolumeHoraire
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
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

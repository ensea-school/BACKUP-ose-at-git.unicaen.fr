<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emploi
 */
class Emploi
{
    /**
     * @var \DateTime
     */
    private $dateFin;

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
    private $intervenantExterieurId;

    /**
     * @var \DateTime
     */
    private $dateDebut;

    /**
     * @var integer
     */
    private $intervenantId;

    /**
     * @var \Application\Entity\Db\Employeur
     */
    private $employeur;


    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Emploi
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set histoCreateur
     *
     * @param integer $histoCreateur
     * @return Emploi
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
     * @return Emploi
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
     * @return Emploi
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
     * @return Emploi
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
     * @return Emploi
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
     * @return Emploi
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
     * Set intervenantExterieurId
     *
     * @param integer $intervenantExterieurId
     * @return Emploi
     */
    public function setIntervenantExterieurId($intervenantExterieurId)
    {
        $this->intervenantExterieurId = $intervenantExterieurId;

        return $this;
    }

    /**
     * Get intervenantExterieurId
     *
     * @return integer 
     */
    public function getIntervenantExterieurId()
    {
        return $this->intervenantExterieurId;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Emploi
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set intervenantId
     *
     * @param integer $intervenantId
     * @return Emploi
     */
    public function setIntervenantId($intervenantId)
    {
        $this->intervenantId = $intervenantId;

        return $this;
    }

    /**
     * Get intervenantId
     *
     * @return integer 
     */
    public function getIntervenantId()
    {
        return $this->intervenantId;
    }

    /**
     * Set employeur
     *
     * @param \Application\Entity\Db\Employeur $employeur
     * @return Emploi
     */
    public function setEmployeur(\Application\Entity\Db\Employeur $employeur)
    {
        $this->employeur = $employeur;

        return $this;
    }

    /**
     * Get employeur
     *
     * @return \Application\Entity\Db\Employeur 
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }
}

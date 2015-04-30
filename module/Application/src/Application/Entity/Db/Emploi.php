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
    protected $dateDebut;

    /**
     * @var \DateTime
     */
    protected $dateFin;

    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var \DateTime
     */
    protected $histoModification;

    /**
     * @var integer
     */
    protected $intervenantId;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Employeur
     */
    protected $employeur;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    protected $intervenantExterieur;


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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Emploi
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return Emploi
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Emploi
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return Emploi
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Emploi
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set employeur
     *
     * @param \Application\Entity\Db\Employeur $employeur
     * @return Emploi
     */
    public function setEmployeur(\Application\Entity\Db\Employeur $employeur = null)
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

    /**
     * Set intervenantExterieur
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenantExterieur
     * @return Emploi
     */
    public function setIntervenantExterieur(\Application\Entity\Db\IntervenantExterieur $intervenantExterieur = null)
    {
        $this->intervenantExterieur = $intervenantExterieur;

        return $this;
    }

    /**
     * Get intervenantExterieur
     *
     * @return \Application\Entity\Db\IntervenantExterieur 
     */
    public function getIntervenantExterieur()
    {
        return $this->intervenantExterieur;
    }
}

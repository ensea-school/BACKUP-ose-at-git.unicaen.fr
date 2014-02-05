<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntervenantExterieur
 */
class IntervenantExterieur extends Intervenant implements HistoInterface
{
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
     * @var string
     */
    private $profession;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\SituationFamiliale
     */
    private $situationFamiliale;

    /**
     * @var \Application\Entity\Db\TypeIntervenantExterieur
     */
    private $type;

    /**
     * @var \Application\Entity\Db\RegimeSecu
     */
    private $regimeSecu;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;


    /**
     * Set histoCreateur
     *
     * @param integer $histoCreateur
     * @return IntervenantExterieur
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
     * @return IntervenantExterieur
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
     * @return IntervenantExterieur
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
     * @return IntervenantExterieur
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
     * @return IntervenantExterieur
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
     * @return IntervenantExterieur
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
     * Set profession
     *
     * @param string $profession
     * @return IntervenantExterieur
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set situationFamiliale
     *
     * @param \Application\Entity\Db\SituationFamiliale $situationFamiliale
     * @return IntervenantExterieur
     */
    public function setSituationFamiliale(\Application\Entity\Db\SituationFamiliale $situationFamiliale = null)
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }

    /**
     * Get situationFamiliale
     *
     * @return \Application\Entity\Db\SituationFamiliale 
     */
    public function getSituationFamiliale()
    {
        return $this->situationFamiliale;
    }

    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypeIntervenantExterieur $type
     * @return IntervenantExterieur
     */
    public function setType(\Application\Entity\Db\TypeIntervenantExterieur $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeIntervenantExterieur 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set regimeSecu
     *
     * @param \Application\Entity\Db\RegimeSecu $regimeSecu
     * @return IntervenantExterieur
     */
    public function setRegimeSecu(\Application\Entity\Db\RegimeSecu $regimeSecu = null)
    {
        $this->regimeSecu = $regimeSecu;

        return $this;
    }

    /**
     * Get regimeSecu
     *
     * @return \Application\Entity\Db\RegimeSecu 
     */
    public function getRegimeSecu()
    {
        return $this->regimeSecu;
    }
}

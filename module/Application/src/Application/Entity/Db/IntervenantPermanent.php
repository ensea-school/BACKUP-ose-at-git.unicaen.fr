<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * IntervenantPermanent
 */
class IntervenantPermanent extends Intervenant implements HistoInterface
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
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $uniteRecherche;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Corps
     */
    private $corps;


    /**
     * Set histoCreateur
     *
     * @param integer $histoCreateur
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * Set uniteRecherche
     *
     * @param \Application\Entity\Db\Structure $uniteRecherche
     * @return IntervenantPermanent
     */
    public function setUniteRecherche(\Application\Entity\Db\Structure $uniteRecherche = null)
    {
        $this->uniteRecherche = $uniteRecherche;

        return $this;
    }

    /**
     * Get uniteRecherche
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getUniteRecherche()
    {
        return $this->uniteRecherche;
    }

    /**
     * Set corps
     *
     * @param \Application\Entity\Db\Corps $corps
     * @return IntervenantPermanent
     */
    public function setCorps(\Application\Entity\Db\Corps $corps = null)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return \Application\Entity\Db\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }
}

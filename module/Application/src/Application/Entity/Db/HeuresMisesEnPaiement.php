<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * HeuresMisesEnPaiement
 */
class HeuresMisesEnPaiement
{
    /**
     * @var float
     */
    private $heuresFa;

    /**
     * @var float
     */
    private $heuresFc;

    /**
     * @var float
     */
    private $heuresFi;

    /**
     * @var float
     */
    private $heuresReferentiel;

    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\MiseEnPaiement
     */
    private $miseEnPaiement;

    /**
     * @var \Application\Entity\Db\CentreCout
     */
    private $centreCout;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;


    /**
     * Set heuresFa
     *
     * @param float $heuresFa
     * @return HeuresMisesEnPaiement
     */
    public function setHeuresFa($heuresFa)
    {
        $this->heuresFa = $heuresFa;

        return $this;
    }

    /**
     * Get heuresFa
     *
     * @return float 
     */
    public function getHeuresFa()
    {
        return $this->heuresFa;
    }

    /**
     * Set heuresFc
     *
     * @param float $heuresFc
     * @return HeuresMisesEnPaiement
     */
    public function setHeuresFc($heuresFc)
    {
        $this->heuresFc = $heuresFc;

        return $this;
    }

    /**
     * Get heuresFc
     *
     * @return float 
     */
    public function getHeuresFc()
    {
        return $this->heuresFc;
    }

    /**
     * Set heuresFi
     *
     * @param float $heuresFi
     * @return HeuresMisesEnPaiement
     */
    public function setHeuresFi($heuresFi)
    {
        $this->heuresFi = $heuresFi;

        return $this;
    }

    /**
     * Get heuresFi
     *
     * @return float 
     */
    public function getHeuresFi()
    {
        return $this->heuresFi;
    }

    /**
     * Set heuresReferentiel
     *
     * @param float $heuresReferentiel
     * @return HeuresMisesEnPaiement
     */
    public function setHeuresReferentiel($heuresReferentiel)
    {
        $this->heuresReferentiel = $heuresReferentiel;

        return $this;
    }

    /**
     * Get heuresReferentiel
     *
     * @return float 
     */
    public function getHeuresReferentiel()
    {
        return $this->heuresReferentiel;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return HeuresMisesEnPaiement
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
     * @return HeuresMisesEnPaiement
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
     * @return HeuresMisesEnPaiement
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
     * Set miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     * @return HeuresMisesEnPaiement
     */
    public function setMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement = null)
    {
        $this->miseEnPaiement = $miseEnPaiement;

        return $this;
    }

    /**
     * Get miseEnPaiement
     *
     * @return \Application\Entity\Db\MiseEnPaiement 
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
    }

    /**
     * Set centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     * @return HeuresMisesEnPaiement
     */
    public function setCentreCout(\Application\Entity\Db\CentreCout $centreCout = null)
    {
        $this->centreCout = $centreCout;

        return $this;
    }

    /**
     * Get centreCout
     *
     * @return \Application\Entity\Db\CentreCout 
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return HeuresMisesEnPaiement
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
     * @return HeuresMisesEnPaiement
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
     * @return HeuresMisesEnPaiement
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
}

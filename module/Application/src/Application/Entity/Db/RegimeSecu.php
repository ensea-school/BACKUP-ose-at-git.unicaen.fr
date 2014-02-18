<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegimeSecu
 */
class RegimeSecu implements HistoInterface
{
    /**
     * @var User
     */
    private $histoCreateur;

    /**
     * @var \DateTime
     */
    private $histoDebut;

    /**
     * @var User
     */
    private $histoDestructeur;

    /**
     * @var \DateTime
     */
    private $histoFin;

    /**
     * @var User
     */
    private $histoModificateur;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var float
     */
    private $tauxTaxe;

    /**
     * @var string
     */
    private $id;

    /**
     * Représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * Set histoCreateur
     *
     * @param User $histoCreateur
     * @return RegimeSecu
     */
    public function setHistoCreateur(User $histoCreateur)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return User 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set histoDebut
     *
     * @param \DateTime $histoDebut
     * @return RegimeSecu
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
     * @return RegimeSecu
     */
    public function setHistoDestructeur(User $histoDestructeur)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return User 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoFin
     *
     * @param \DateTime $histoFin
     * @return RegimeSecu
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
     * @param User $histoModificateur
     * @return RegimeSecu
     */
    public function setHistoModificateur(User $histoModificateur)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return User 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return RegimeSecu
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
     * Set id
     *
     * @param string $id
     * @return RegimeSecu
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return RegimeSecu
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set tauxTaxe
     *
     * @param float $tauxTaxe
     * @return RegimeSecu
     */
    public function setTauxTaxe($tauxTaxe)
    {
        $this->tauxTaxe = $tauxTaxe;

        return $this;
    }

    /**
     * Get tauxTaxe
     *
     * @return float 
     */
    public function getTauxTaxe()
    {
        return $this->tauxTaxe;
    }
}

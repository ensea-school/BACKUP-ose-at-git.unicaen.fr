<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etablissement
 */
class Etablissement implements HistoInterface
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
    private $libelle;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set histoCreateur
     *
     * @param integer $histoCreateur
     * @return Etablissement
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
     * @return Etablissement
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
     * @return Etablissement
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
     * @return Etablissement
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
     * @return Etablissement
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
     * @return Etablissement
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
     * Set libelle
     *
     * @param string $libelle
     * @return Etablissement
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}

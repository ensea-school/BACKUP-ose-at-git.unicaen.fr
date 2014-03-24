<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementSection
 */
class ElementSection
{
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
     * @var string
     */
    private $sourceCode;

    /**
     * @var \DateTime
     */
    private $validiteDebut;

    /**
     * @var \DateTime
     */
    private $validiteFin;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    private $elementPedagogique;

    /**
     * @var \Application\Entity\Db\SectionCnu
     */
    private $sectionCnu;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return ElementSection
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
     * @return ElementSection
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
     * @return ElementSection
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
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return ElementSection
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string 
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return ElementSection
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return ElementSection
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
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
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return ElementSection
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }

    /**
     * Get elementPedagogique
     *
     * @return \Application\Entity\Db\ElementPedagogique 
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }

    /**
     * Set sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     * @return ElementSection
     */
    public function setSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu = null)
    {
        $this->sectionCnu = $sectionCnu;

        return $this;
    }

    /**
     * Get sectionCnu
     *
     * @return \Application\Entity\Db\SectionCnu 
     */
    public function getSectionCnu()
    {
        return $this->sectionCnu;
    }

    /**
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return ElementSection
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return ElementSection
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
     * @return ElementSection
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
     * @return ElementSection
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

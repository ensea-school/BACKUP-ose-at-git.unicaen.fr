<?php

namespace Application\Entity\Db;

/**
 * TypeHeures
 */
class TypeHeures
{
    const FI          = 'fi';
    const FA          = 'fa';
    const FC          = 'fc';
    const FC_MAJOREES = 'fc_majorees';
    const REFERENTIEL = 'referentiel';

    /**
     * @var string
     */
    private $code;

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
    private $libelleCourt;

    /**
     * @var string
     */
    private $libelleLong;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var boolean
     */
    private $eligibleCentreCoutEp;

    /**
     * @var integer
     */
    private $id;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $centreCout;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $elementPedagogique;

    /**
     *
     * @var TypeHeures
     */
    private $typeHeuresElement;


    /**
     * Set code
     *
     * @param string $code
     * @return TypeHeures
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return TypeHeures
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
     * @return TypeHeures
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
     * @return TypeHeures
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
     * Set libelleCourt
     *
     * @param string $libelleCourt
     * @return TypeHeures
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }

    /**
     * Get libelleCourt
     *
     * @return string 
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     * @return TypeHeures
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }

    /**
     * Get libelleLong
     *
     * @return string 
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return TypeHeures
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set eligibleCentreCoutEp
     *
     * @param boolean $eligibleCentreCoutEp
     * @return TypeHeures
     */
    public function setEligibleCentreCoutEp($eligibleCentreCoutEp)
    {
        $this->eligibleCentreCoutEp = $eligibleCentreCoutEp;

        return $this;
    }

    /**
     * Get eligibleCentreCoutEp
     *
     * @return boolean 
     */
    public function getEligibleCentreCoutEp()
    {
        return $this->eligibleCentreCoutEp;
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
     * @return TypeHeures
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
     * @return TypeHeures
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
     * @return TypeHeures
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
     * Get centreCout
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }

    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }

    /**
     *
     * @return type
     */
    function getTypeHeuresElement()
    {
        return $this->typeHeuresElement;
    }

    /**
     *
     * @param \Application\Entity\Db\TypeHeures $typeHeuresElement
     * @return \Application\Entity\Db\TypeHeures
     */
    function setTypeHeuresElement(TypeHeures $typeHeuresElement)
    {
        $this->typeHeuresElement = $typeHeuresElement;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }

    /**
     *
     * @return string
     */
    public function toHtml()
    {
        return '<abbr title="'.$this->getLibelleLong().'">'.$this->getLibelleCourt().'</abbr>';
    }
}
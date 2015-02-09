<?php

namespace Application\Entity\Db;

/**
 * Structure
 */
class Structure implements HistoriqueAwareInterface, ValiditeAwareInterface
{
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
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var integer
     */
    protected $niveau;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var string
     */
    protected $contactPj;

    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\TypeStructure
     */
    protected $type;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Etablissement
     */
    protected $etablissement;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $parente;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structureNiv2;

    /**
     * @var \Doctrine\Common\Collections\Collection 
     */
    protected $elementPedagogique;

    /**
     * @var \Doctrine\Common\Collections\Collection 
     */
    protected $service;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $centreCout;





    function __construct()
    {
        $this->structureNiv2        = new \Doctrine\Common\Collections\Collection;
        $this->elementPedagogique   = new \Doctrine\Common\Collections\Collection;
        $this->service              = new \Doctrine\Common\Collections\Collection;
        $this->centreCout           = new \Doctrine\Common\Collections\Collection;
    }


    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Structure
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
     * @return Structure
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
     * @return Structure
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
     * @return Structure
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
     * @return Structure
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
     * Set niveau
     *
     * @param integer $niveau
     * @return Structure
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return integer 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return Structure
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
     * Set contactPj
     *
     * @param string $contactPj
     * @return Structure
     */
    public function setContactPj($contactPj)
    {
        $this->contactPj = $contactPj;

        return $this;
    }

    /**
     * Get contactPj
     *
     * @return string 
     */
    public function getContactPj()
    {
        return $this->contactPj;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return Structure
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
     * @return Structure
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return Structure
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
     * Set type
     *
     * @param \Application\Entity\Db\TypeStructure $type
     * @return Structure
     */
    public function setType(\Application\Entity\Db\TypeStructure $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeStructure 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Structure
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
     * @return Structure
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
     * Set etablissement
     *
     * @param \Application\Entity\Db\Etablissement $etablissement
     * @return Structure
     */
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \Application\Entity\Db\Etablissement 
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Structure
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
     * Set parente
     *
     * @param \Application\Entity\Db\Structure $parente
     * @return Structure
     */
    public function setParente(\Application\Entity\Db\Structure $parente = null)
    {
        $this->parente = $parente;

        return $this;
    }

    /**
     * Get parente
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getParente()
    {
        return $this->parente;
    }

    /**
     * Set structureNiv2
     *
     * @param \Application\Entity\Db\Structure $structureNiv2
     * @return Structure
     */
    public function setParenteNiv2(\Application\Entity\Db\Structure $structureNiv2 = null)
    {
        $this->structureNiv2 = $structureNiv2;

        return $this;
    }

    /**
     * Get structureNiv2
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getParenteNiv2()
    {
        return $this->structureNiv2;
    }

    /**
     * Add elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return Intervenant
     */
    public function addElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique[] = $elementPedagogique;

        return $this;
    }

    /**
     * Remove elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     */
    public function removeElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique->removeElement($elementPedagogique);
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
     * Add service
     *
     * @param \Application\Entity\Db\Service $service
     * @return Intervenant
     */
    public function addService(\Application\Entity\Db\Service $service)
    {
        $this->service[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Application\Entity\Db\Service $service
     */
    public function removeService(\Application\Entity\Db\Service $service)
    {
        $this->service->removeElement($service);
    }

    /**
     * Get service
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Add centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     * @return Intervenant
     */
    public function addCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {
        $this->centreCout[] = $centreCout;

        return $this;
    }

    /**
     * Remove centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     */
    public function removeCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {
        $this->service->removeElement($centreCout);
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

    /**************************************************************************************************
     *                                      Début ajout
     **************************************************************************************************/

    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }

    /**
     * Get source id
     *
     * @return integer 
     * @see \Application\Entity\Db\Source
     */
    public function getSourceToString()
    {
        return $this->getSource()->getLibelle();
    }

    /**
     * Teste si cette structure est une structure fille de la structure de niveau 2 spécifiée.
     *
     * @param \Application\Entity\Db\Structure $structureDeNiv2 
     * @return bool 
     */
    public function estFilleDeLaStructureDeNiv2(\Application\Entity\Db\Structure $structureDeNiv2)
    {
        return $this->getParenteNiv2()->getId() === $structureDeNiv2->getId();
    }
}

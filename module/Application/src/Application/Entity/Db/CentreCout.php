<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CentreCout
 */
class CentreCout implements HistoriqueAwareInterface
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
    private $libelle;

    /**
     * @var CentreCout
     */
    private $parent;

    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\TypeRessource
     */
    private $typeRessource;

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
    private $histoCreateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\CcActivite
     */
    private $activite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeHeures;

    /**
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    private $miseEnPaiement;


    public function __construct()
    {
        $this->typeHeures       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->miseEnPaiement   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return CentreCout
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
     * @return CentreCout
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
     * @return CentreCout
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
     * @return CentreCout
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
     * Set parent
     *
     * @param CentreCout $parent
     * @return CentreCout
     */
    public function setParent( CentreCout $parent = null )
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return CentreCout
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return CentreCout
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return CentreCout
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set typeRessource
     *
     * @param \Application\Entity\Db\TypeRessource $typeRessource
     * @return CentreCout
     */
    public function setTypeRessource(\Application\Entity\Db\TypeRessource $typeRessource = null)
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }

    /**
     * Get typeRessource
     *
     * @return \Application\Entity\Db\TypeRessource 
     */
    public function getTypeRessource()
    {
        return $this->typeRessource;
    }

    /**
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return CentreCout
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
     * @return CentreCout
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
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return CentreCout
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
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return CentreCout
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
     * Set activite
     *
     * @param \Application\Entity\Db\CcActivite $activite
     * @return CentreCout
     */
    public function setActivite(\Application\Entity\Db\CcActivite $activite = null)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return \Application\Entity\Db\CcActivite 
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Get typeHeures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getSourceCode().' - '.$this->getLibelle();
    }

    /**
     * détermine si un type d'heures peut être appliqué à ce type de ressource ou non
     *
     * @param \Application\Entity\Db\TypeHeures $typeHeures
     * @return boolean
     */
    public function typeHeuresMatches( TypeHeures $typeHeures )
    {
        return $this->getActivite()->typeHeuresMatches($typeHeures) && $this->getTypeRessource()->typeHeuresMatches($typeHeures);
    }

    /**
     * Add miseEnPaiement
     *
     * @param MiseEnPaiement $miseEnPaiement
     * @return self
     */
    public function addMiseEnPaiement(MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement[] = $miseEnPaiement;

        return $this;
    }

    /**
     * Remove miseEnPaiement
     *
     * @param MiseEnPaiement $miseEnPaiement
     */
    public function removeMiseEnPaiement(MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement->removeElement($miseEnPaiement);
    }

    /**
     * Get miseEnPaiement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
    }
}

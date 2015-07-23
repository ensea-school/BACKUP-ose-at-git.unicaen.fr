<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * CentreCout
 */
class CentreCout implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;


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

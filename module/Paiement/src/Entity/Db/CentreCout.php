<?php

namespace Paiement\Entity\Db;

use OffreFormation\Entity\Db\TypeHeures;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * CentreCout
 */
class CentreCout implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /***
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var CentreCout
     */
    private $parent;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $structure;

    /**
     * @var \Paiement\Entity\Db\TypeRessource
     */
    private $typeRessource;

    /**
     * @var \Paiement\Entity\Db\CcActivite
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

    /**
     * @var string
     */
    private $uniteBudgetaire;





    public function __construct()
    {
        $this->typeHeures     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->miseEnPaiement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->structure      = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return CentreCout
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
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
     *
     * @return CentreCout
     */
    public function setParent(?CentreCout $parent = null)
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Get structure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set typeRessource
     *
     * @param \Paiement\Entity\Db\TypeRessource $typeRessource
     *
     * @return CentreCout
     */
    public function setTypeRessource(?\Paiement\Entity\Db\TypeRessource $typeRessource = null)
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }



    /**
     * Get typeRessource
     *
     * @return \Paiement\Entity\Db\TypeRessource
     */
    public function getTypeRessource()
    {
        return $this->typeRessource;
    }



    /**
     * Set activite
     *
     * @param \Paiement\Entity\Db\CcActivite $activite
     *
     * @return CentreCout
     */
    public function setActivite(?\Paiement\Entity\Db\CcActivite $activite = null)
    {
        $this->activite = $activite;

        return $this;
    }



    /**
     * Get activite
     *
     * @return \Paiement\Entity\Db\CcActivite
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
     * Set libelle
     *
     * @param string $uniteBudgetaire
     *
     * @return CentreCout
     */
    public function setUniteBudgetaire($uniteBudgetaire)
    {
        $this->uniteBudgetaire = $uniteBudgetaire;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getUniteBudgetaire()
    {
        return $this->uniteBudgetaire;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCode() . ' - ' . $this->getLibelle();
    }



    /**
     * détermine si un type d'heures peut être appliqué à ce type de ressource ou non
     *
     * @param \OffreFormation\Entity\Db\TypeHeures $typeHeures
     *
     * @return boolean
     */
    public function typeHeuresMatches(TypeHeures $typeHeures)
    {
        return $this->getActivite()->typeHeuresMatches($typeHeures) && $this->getTypeRessource()->typeHeuresMatches($typeHeures);
    }



    /**
     * Add miseEnPaiement
     *
     * @param MiseEnPaiement $miseEnPaiement
     *
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

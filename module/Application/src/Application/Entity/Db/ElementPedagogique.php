<?php

namespace Application\Entity\Db;

/**
 * ElementPedagogique
 */
class ElementPedagogique implements HistoriqueAwareInterface, ValiditeAwareInterface
{
    public function __toString()
    {
        return $this->getSourceCode().' - '.$this->getLibelle();
    }
    
    /**
     * Retourne les étapes auxquelles est lié cet élément pédagogique.
     * 
     * @param bool $principaleIncluse Faut-il inclure l'étape principale ou non ?
     * @return array
     */
    public function getEtapes($principaleIncluse = true)
    {
        $etapePrincipale = $this->getEtape();
        $etapes = array();
        
        if (($chemins = $this->getCheminPedagogique())) {
            foreach ($this->getCheminPedagogique() as $cp) { /* @var $cp \Application\Entity\Db\CheminPedagogique */
                if (!$principaleIncluse && $etapePrincipale === $cp->getEtape()) {
                    continue;
                }
                $etapes[$cp->getOrdre()] = $cp->getEtape();
            }
            ksort($etapes);
        }
        
        return $etapes;
    }
        
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
    protected $libelle;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var integer
     */
    protected $tauxFoad;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $cheminPedagogique;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\Periode
     */
    protected $periode;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Etape
     */
    protected $etape;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cheminPedagogique = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return ElementPedagogique
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
     * Set tauxFoad
     *
     * @param integer $tauxFoad
     * @return ElementPedagogique
     */
    public function setTauxFoad($tauxFoad)
    {
        $this->tauxFoad = $tauxFoad;

        return $this;
    }

    /**
     * Get tauxFoad
     *
     * @return integer 
     */
    public function getTauxFoad()
    {
        return $this->tauxFoad;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return ElementPedagogique
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
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return ElementPedagogique
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * @return ElementPedagogique
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
     * Set etape
     *
     * @param \Application\Entity\Db\Etape $etape
     * @return ElementPedagogique
     */
    public function setEtape(\Application\Entity\Db\Etape $etape = null)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return \Application\Entity\Db\Etape 
     */
    public function getEtape()
    {
        return $this->etape;
    }

    /**
     * Add cheminPedagogique
     *
     * @param \Application\Entity\Db\CheminPedagogique $cheminPedagogique
     * @return Etape
     */
    public function addCheminPedagogique(\Application\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique[] = $cheminPedagogique;

        return $this;
    }

    /**
     * Remove cheminPedagogique
     *
     * @param \Application\Entity\Db\CheminPedagogique $cheminPedagogique
     */
    public function removeCheminPedagogique(\Application\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique->removeElement($cheminPedagogique);
    }

    /**
     * Get cheminPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCheminPedagogique()
    {
        return $this->cheminPedagogique;
    }
}
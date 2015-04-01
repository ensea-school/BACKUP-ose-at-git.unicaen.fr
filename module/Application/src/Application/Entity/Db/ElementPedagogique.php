<?php

namespace Application\Entity\Db;

/**
 * ElementPedagogique
 */
class ElementPedagogique implements HistoriqueAwareInterface
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
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;

    /**
     * @var integer
     */
    protected $tauxFoad;

    /**
     * FI
     *
     * @var float
     */
    protected $tauxFi;

    /**
     * FC
     *
     * @var float
     */
    protected $tauxFc;

    /**
     * FA
     *
     * @var float
     */
    protected $tauxFa;

    /**
     * FI
     *
     * @var boolean
     */
    protected $fi;

    /**
     * FC
     *
     * @var boolean
     */
    protected $fc;

    /**
     * FA
     *
     * @var boolean
     */
    protected $fa;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $cheminPedagogique;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $service;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $elementModulateur;

    /**
     * haschanged
     *
     * @var boolean
     */
    protected $hasChanged;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeIntervention;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeModulateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeHeures;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $centreCoutEp;

    


    public function getHasChanged()
    {
        return $this->hasChanged;
    }

    public function setHasChanged($hasChanged)
    {
        $this->hasChanged = $hasChanged;
        return $this;
    }

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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Service
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set tauxFoad
     *
     * @param float $tauxFoad
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
     * @return float
     */
    public function getTauxFoad()
    {
        return $this->tauxFoad;
    }

    /**
     *
     * @return boolean
     */
    public function getFi()
    {
        return $this->fi;
    }

    /**
     *
     * @return boolean
     */
    public function getFc()
    {
        return $this->fc;
    }

    /**
     *
     * @return boolean
     */
    public function getFa()
    {
        return $this->fa;
    }

    /**
     *
     * @return float
     */
    public function getTauxFi()
    {
        return $this->tauxFi;
    }

    /**
     *
     * @return float
     */
    public function getTauxFc()
    {
        return $this->tauxFc;
    }

    /**
     *
     * @return float
     */
    public function getTauxFa()
    {
        return $this->tauxFa;
    }

/**
     *
     * @param boolean $fi
     * @return self
     */
    public function setFi($fi)
    {
        $this->fi = $fi;
        return $this;
    }

    /**
     *
     * @param boolean $fc
     * @return self
     */
    public function setFc($fc)
    {
        $this->fc = $fc;
        return $this;
    }

    /**
     *
     * @param boolean $fa
     * @return self
     */
    public function setFa($fa)
    {
        $this->fa = $fa;
        return $this;
    }

    /**
     *
     * @param float $tauxFi
     * @return self
     */
    public function setTauxFi($tauxFi)
    {
        $this->tauxFi = $tauxFi;
        return $this;
    }

    /**
     *
     * @param float $tauxFc
     * @return self
     */
    public function setTauxFc($tauxFc)
    {
        $this->tauxFc = $tauxFc;
        return $this;
    }

    /**
     *
     * @param float $tauxFa
     * @return self
     */
    public function setTauxFa($tauxFa)
    {
        $this->tauxFa = $tauxFa;
        return $this;
    }

    /**
     * Retourne, sous forme de chaîne de caractères, la liste des régimes d'inscription
     *
     * @param boolean $inHtml   Détermine si le résultat doit ou non être formatté en HTML
     * @return string
     */
    public function getRegimesInscription( $inHtml = false )
    {
        $regimes = [];
        if ($inHtml){
            if ($this->getFi()) $regimes[] = '<abbr title="Formation initiale ('.number_format($this->getTauxFi()*100, 2, ',', ' ').'%)">FI</abbr>';
            if ($this->getFc()) $regimes[] = '<abbr title="Formation continue ('.number_format($this->getTauxFc()*100, 2, ',', ' ').'%)">FC</abbr>';
            if ($this->getFa()) $regimes[] = '<abbr title="Formation en apprentissage ('.number_format($this->getTauxFa()*100, 2, ',', ' ').'%)">FA</abbr>';
        }else{
            if ($this->getFi()) $regimes[] = 'FI';
            if ($this->getFc()) $regimes[] = 'FC';
            if ($this->getFa()) $regimes[] = 'FA';
        }
        return implode( ', ', $regimes );
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

    /**
     * Add elementModulateur
     *
     * @param ElementModulateur $elementModulateur
     * @return ElementPedagogique
     */
    public function addElementModulateur(ElementModulateur $elementModulateur)
    {
        $this->elementModulateur[] = $elementModulateur;

        return $this;
    }

    /**
     * Remove elementModulateur
     *
     * @param ElementModulateur $elementModulateur
     */
    public function removeElementModulateur(ElementModulateur $elementModulateur)
    {
        $this->elementModulateur->removeElement($elementModulateur);
    }

    /**
     * Get elementModulateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementModulateur()
    {
        return $this->elementModulateur;
    }

    /**
     * Add service
     *
     * @param \Application\Entity\Db\Service $service
     * @return Service
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
     * Get typeIntervention
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }

    /**
     * Get typeModulateur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeModulateur()
    {
        return $this->typeModulateur;
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
     * Add centreCoutEp
     *
     * @param \Application\Entity\Db\CentreCoutEp $centreCoutEp
     * @return CentreCoutEp
     */
    public function addCentreCoutEp(\Application\Entity\Db\CentreCoutEp $centreCoutEp)
    {
        $this->centreCoutEp[] = $centreCoutEp;

        return $this;
    }

    /**
     * Remove centreCoutEp
     *
     * @param \Application\Entity\Db\CentreCoutEp $centreCoutEp
     */
    public function removeCentreCoutEp(\Application\Entity\Db\CentreCoutEp $centreCoutEp)
    {
        $this->centreCoutEp->removeElement($centreCoutEp);
    }

    /**
     * Get centreCoutEp
     *
     * @param \Application\Entity\Db\TypeHeures $th Eventuel seul type d'heures à prendre en compte
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCoutEp(TypeHeures $th = null)
    {
        if (!$th) {
            return $this->centreCoutEp;
        }
        
        $f     = function(CentreCoutEp $ccEp) use ($th) { return $ccEp->getTypeHeures() === $th; };
        $slice = $this->centreCoutEp->filter($f);
        
        if (count($slice) > 1) {
            throw new \Common\Exception\LogicException(sprintf(
                    "Anomalie dans la base de données : plus d'un centre de coûts trouvé pour l'élément pédagogique %s et le type d'heures %s.",
                    $this,
                    $th));
        }
        
        return $slice;
    }
}
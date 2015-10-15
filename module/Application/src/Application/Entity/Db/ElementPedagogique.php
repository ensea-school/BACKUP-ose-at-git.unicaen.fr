<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\AnneeAwareInterface;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\DisciplineAwareTrait;
use Application\Entity\Db\Traits\EtapeAwareTrait;
use Application\Entity\Db\Traits\PeriodeAwareTrait;
use Application\Entity\Db\Traits\SourceAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * ElementPedagogique
 */
class ElementPedagogique implements HistoriqueAwareInterface, AnneeAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use DisciplineAwareTrait;
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use PeriodeAwareTrait;
    use SourceAwareTrait;
    use EtapeAwareTrait;



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



    public function __toString()
    {
        return $this->getSourceCode() . ' - ' . $this->getLibelle();
    }



    /**
     * Retourne les étapes auxquelles est lié cet élément pédagogique.
     *
     * @param bool $principaleIncluse Faut-il inclure l'étape principale ou non ?
     *
     * @return array
     */
    public function getEtapes($principaleIncluse = true)
    {
        $etapePrincipale = $this->getEtape();
        $etapes          = [];

        if (($chemins = $this->getCheminPedagogique())) {
            foreach ($this->getCheminPedagogique() as $cp) {
                /* @var $cp \Application\Entity\Db\CheminPedagogique */
                if (!$principaleIncluse && $etapePrincipale === $cp->getEtape()) {
                    continue;
                }
                $etapes[$cp->getOrdre()] = $cp->getEtape();
            }
            ksort($etapes);
        }

        return $etapes;
    }



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
     * Set libelle
     *
     * @param string $libelle
     *
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
     *
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
     * @param float $tauxFoad
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     * @param boolean $inHtml Détermine si le résultat doit ou non être formatté en HTML
     *
     * @return string
     */
    public function getRegimesInscription($inHtml = false)
    {
        $regimes = [];
        if ($inHtml) {
            if ($this->getFi()) $regimes[] = '<abbr title="Formation initiale (' . number_format($this->getTauxFi() * 100, 2, ',', ' ') . '%)">FI</abbr>';
            if ($this->getFc()) $regimes[] = '<abbr title="Formation continue (' . number_format($this->getTauxFc() * 100, 2, ',', ' ') . '%)">FC</abbr>';
            if ($this->getFa()) $regimes[] = '<abbr title="Formation en apprentissage (' . number_format($this->getTauxFa() * 100, 2, ',', ' ') . '%)">FA</abbr>';
        } else {
            if ($this->getFi()) $regimes[] = 'FI';
            if ($this->getFc()) $regimes[] = 'FC';
            if ($this->getFa()) $regimes[] = 'FA';
        }

        return implode(', ', $regimes);
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
     * Add cheminPedagogique
     *
     * @param \Application\Entity\Db\CheminPedagogique $cheminPedagogique
     *
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
     *
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
     *
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
     *
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
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCoutEp(TypeHeures $th = null)
    {
        if (!$th) {
            return $this->centreCoutEp;
        }

        $f     = function (CentreCoutEp $ccEp) use ($th) {
            return $ccEp->getTypeHeures() === $th;
        };
        $slice = $this->centreCoutEp->filter($f);

        if (count($slice) > 1) {
            throw new \Common\Exception\LogicException(sprintf(
                "Anomalie dans la base de données : plus d'un centre de coûts trouvé pour l'élément pédagogique %s et le type d'heures %s.",
                $this,
                $th));
        }

        return $slice;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'ElementPedagogique';
    }

}
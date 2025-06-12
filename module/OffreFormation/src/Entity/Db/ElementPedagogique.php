<?php

namespace OffreFormation\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Traits\ChampsAutresTrait;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\PeriodeAwareTrait;
use Enseignement\Entity\Db\Service;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;
use OffreFormation\Entity\Db\Traits\DisciplineAwareTrait;
use OffreFormation\Entity\Db\Traits\EtapeAwareTrait;
use Paiement\Entity\Db\TauxRemu;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use function count;


/**
 * ElementPedagogique
 */
class ElementPedagogique implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, EntityManagerAwareInterface, PlafondPerimetreInterface, PlafondDataInterface, ChampsAutresInterface
{
    use HistoriqueAwareTrait;
    use DisciplineAwareTrait;
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use PeriodeAwareTrait;
    use EtapeAwareTrait;
    use ImportAwareTrait;
    use EntityManagerAwareTrait;
    use ChampsAutresTrait;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $volumeHoraireEns;

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
    private   $centreCoutEp;

    protected $tauxRemuEp = null;

    /**
     * @var \OffreFormation\Entity\Db\Effectifs
     */
    private $effectifs = false;



    public function __toString()
    {
        return $this->getCode() . ' - ' . $this->getLibelle();
    }



    /**
     * Retourne les étapes auxquelles est lié cet élément pédagogique.
     *
     * @param bool $principaleIncluse Faut-il inclure l'étape principale ou non ?
     *
     * @return array
     */
    public function getEtapes(bool $principaleIncluse = true)
    {
        $etapePrincipale = $this->getEtape();
        $etapes          = [];

        if (($chemins = $this->getCheminPedagogique())) {
            foreach ($this->getCheminPedagogique() as $cp) {
                /* @var $cp \OffreFormation\Entity\Db\CheminPedagogique */
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return ElementPedagogique
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
     * Set un taux de rémunération
     *
     * @param TauxRemu|null $tauxRemuEp
     */
    public function setTauxRemuEp(?TauxRemu $tauxRemuEp)
    {
        $this->tauxRemuEp = $tauxRemuEp;
    }



    /**
     * Get centreCout
     *
     * @return TauxRemu|null
     */
    public function getTauxRemuEp(): ?TauxRemu
    {
        return $this->tauxRemuEp;
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
     * @param TypeHeures $typeHeures
     *
     * @return float|null
     */
    public function getTaux(TypeHeures $typeHeures)
    {
        switch ($typeHeures->getCode()) {
            case TypeHeures::FI:
                return $this->getTauxFi();
            case TypeHeures::FC:
                return $this->getTauxFc();
            case TypeHeures::FA:
                return $this->getTauxFa();
        }

        return null;
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
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * Add cheminPedagogique
     *
     * @param \OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique
     *
     * @return Etape
     */
    public function addCheminPedagogique(\OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique[] = $cheminPedagogique;

        return $this;
    }



    /**
     * Remove cheminPedagogique
     *
     * @param \OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique
     */
    public function removeCheminPedagogique(\OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique)
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
     * Add volumeHoraireEns
     *
     * @param VolumeHoraireEns $volumeHoraireEns
     *
     * @return ElementPedagogique
     */
    public function addVolumeHoraireEns(VolumeHoraireEns $volumeHoraireEns)
    {
        $this->volumeHoraireEns[] = $volumeHoraireEns;

        return $this;
    }



    /**
     * Remove volumeHoraireEns
     *
     * @param VolumeHoraireEns $volumeHoraireEns
     */
    public function removeVolumeHoraireEns(VolumeHoraireEns $volumeHoraireEns)
    {
        $this->volumeHoraireEns->removeElement($volumeHoraireEns);
    }



    /**
     * Get volumeHoraireEns
     *
     * @return \Doctrine\Common\Collections\Collection|VolumeHoraireEns[]
     */
    public function getVolumeHoraireEns()
    {
        return $this->volumeHoraireEns;
    }



    /**
     * Add service
     *
     * @param \Enseignement\Entity\Db\Service $service
     *
     * @return Service
     */
    public function addService(\Enseignement\Entity\Db\Service $service)
    {
        $this->service[] = $service;

        return $this;
    }



    /**
     * Remove service
     *
     * @param \Enseignement\Entity\Db\Service $service
     */
    public function removeService(\Enseignement\Entity\Db\Service $service)
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
     * @param \OffreFormation\Entity\Db\CentreCoutEp $centreCoutEp
     *
     * @return CentreCoutEp
     */
    public function addCentreCoutEp(\OffreFormation\Entity\Db\CentreCoutEp $centreCoutEp)
    {
        $this->centreCoutEp[] = $centreCoutEp;

        return $this;
    }



    /**
     * Remove centreCoutEp
     *
     * @param \OffreFormation\Entity\Db\CentreCoutEp $centreCoutEp
     */
    public function removeCentreCoutEp(\OffreFormation\Entity\Db\CentreCoutEp $centreCoutEp)
    {
        $this->centreCoutEp->removeElement($centreCoutEp);
    }


    /**
     * Add centreCoutEp
     *
     * @param \Paiement\Entity\Db\TauxRemu $tauxRemuEp
     *
     * @return ElementPedagogique
     */
    public function addTauxRemuEp(\Paiement\Entity\Db\TauxRemu $tauxRemuEp)
    {
        $this->tauxRemuEp[] = $tauxRemuEp;

        return $this;
    }



    /**
     * Remove centreCoutEp
     *
     * @param \Paiement\Entity\Db\TauxRemu $tauxRemuEp
     */
    public function removeTauxRemuEp(\Paiement\Entity\Db\TauxRemu $tauxRemuEp)
    {
        $this->tauxRemuEp->removeElement($tauxRemuEp);
    }



    /**
     * Get centreCoutEp
     *
     * @param \OffreFormation\Entity\Db\TypeHeures $th Eventuel seul type d'heures à prendre en compte
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCoutEp(?TypeHeures $th = null): \Doctrine\Common\Collections\Collection
    {
        if (!$th) {
            return $this->centreCoutEp;
        }

        $f     = function (CentreCoutEp $ccEp) use ($th) {
            return ($ccEp->getTypeHeures() === $th) && empty($ccEp->getHistoDestruction());
        };
        $slice = $this->centreCoutEp->filter($f);

        if (count($slice) > 1) {
            throw new \LogicException(sprintf(
                "Anomalie dans la base de données : plus d'un centre de coûts trouvé pour l'élément pédagogique %s et le type d'heures %s.",
                $this,
                $th));
        }

        return $slice;
    }



    /**
     * Get effectifs
     *
     * @return \OffreFormation\Entity\Db\Effectifs
     */
    public function getEffectifs()
    {
        if (false === $this->effectifs) {
            $this->effectifs = $this->getEntityManager()->getRepository(Effectifs::class)->findOneBy([
                'elementPedagogique' => $this,
            ]);
        }

        return $this->effectifs;
    }



    /**
     * @return TypeIntervention[]
     */
    public function getTypesInterventionPossibles()
    {
        if (!$this->getId()) return [];

        $sql = 'SELECT TYPE_INTERVENTION_ID FROM V_ELEMENT_TYPE_INTERV_POSSIBLE WHERE ELEMENT_PEDAGOGIQUE_ID = :element';
        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['element' => $this->getId()]);

        $ids = [];
        foreach ($res as $r) {
            $ids[] = (int)$r['TYPE_INTERVENTION_ID'];
        }

        return $this->getEntityManager()->getRepository(TypeIntervention::class)->findBy(['id' => $ids], ['ordre' => 'ASC']);
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



    function __sleep()
    {
        return [];
    }

}
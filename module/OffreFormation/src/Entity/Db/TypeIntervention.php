<?php

namespace OffreFormation\Entity\Db;

use Application\Entity\Db\Annee;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeIntervention
 */
class TypeIntervention implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var integer
     */
    private $id;

    /**
     *
     * @var float
     */
    private $tauxHetdService;

    /**
     *
     * @var float
     */
    private $tauxHetdComplementaire;

    /**
     * visible
     *
     * @var boolean
     */
    private $visible;

    /**
     * visible extérieur
     *
     * @var boolean
     */
    private $visibleExterieur;

    /**
     * anneeDebut
     *
     * @var Annee
     */
    private $anneeDebut;

    /**
     * anneeFin
     *
     * @var Annee
     */
    private $anneeFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeInterventionStructure;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeInterventionStatut;

    /**
     * regleFOAD
     *
     * @var boolean
     */
    private $regleFOAD;

    /**
     * regleFC
     *
     * @var boolean
     */
    private $regleFC;



    public function __toString()
    {
        return (string)$this->getCode();
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeIntervention
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



    public function isVisible(?Structure $structure = null, ?Annee $annee = null)
    {
        if ($structure && $annee) {
            $lst = $this->getTypeInterventionStructure($structure, $annee);
            if ($lst->count() == 1) {
                /** @var TypeInterventionStructure $tis */
                $tis = $lst->first();

                return $tis->isVisible();
            }
        }

        return $this->visible;
    }



    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }



    /**
     * @return bool
     */
    public function isVisibleExterieur()
    {
        return $this->visibleExterieur;
    }



    /**
     * @param bool $visibleExterieur
     *
     * @return TypeIntervention
     */
    public function setVisibleExterieur(bool $visibleExterieur): TypeIntervention
    {
        $this->visibleExterieur = $visibleExterieur;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeDebut()
    {
        return $this->anneeDebut;
    }



    /**
     * @param Annee $anneeDebut
     *
     * @return TypeIntervention
     */
    public function setAnneeDebut($anneeDebut)
    {
        $this->anneeDebut = $anneeDebut;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeFin()
    {
        return $this->anneeFin;
    }



    /**
     * @param Annee $anneeFin
     *
     * @return TypeIntervention
     */
    public function setAnneeFin($anneeFin)
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }



    /**
     * @param Annee          $annee
     * @param Structure|null $structure
     *
     * @return bool
     */
    public function isValide(Annee $annee, ?Structure $structure = null): bool
    {
        /** @var TypeInterventionStructure[] $tisList */
        $tisList = $this->getTypeInterventionStructure()->filter(function (TypeInterventionStructure $tis) use ($structure) {
            if (!$tis->estNonHistorise()) {
                return false;
            }
            if ($structure && $tis->setStructure() != $structure) {
                return false;
            }

            return true;
        });

        if ($tisList->count() > 0) {
            foreach ($tisList as $tis) {
                if ($tis->isValide($annee)) return true;
            }

            return false;
        } else {
            $deb = $this->getAnneeDebut() ? $this->getAnneeDebut()->getId() : 0;
            $fin = $this->getAnneeFin() ? $this->getAnneeFin()->getId() : 99999;
            $a   = $annee->getId();

            return $a >= $deb && $a <= $fin;
        }
    }



    /**
     * @return bool
     */
    public function getRegleFOAD()
    {
        return $this->regleFOAD;
    }



    /**
     * @param bool $regleFOAD
     *
     * @return TypeIntervention
     */
    public function setRegleFOAD($regleFOAD)
    {
        $this->regleFOAD = $regleFOAD;

        return $this;
    }



    /**
     * @return bool
     */
    public function getRegleFC()
    {
        return $this->regleFC;
    }



    /**
     * @param bool $regleFC
     *
     * @return TypeIntervention
     */
    public function setRegleFC($regleFC)
    {
        $this->regleFC = $regleFC;

        return $this;
    }



    /**
     * @return string[]
     */
    public function getRegles(): array
    {
        $regles = [];
        if ($this->getRegleFC()) $regles[] = 'FC';
        if ($this->getRegleFOAD()) $regles[] = 'FOAD';

        return $regles;
    }



    /**
     * Add typeInterventionStructure
     *
     * @param TypeInterventionStructure $typeInterventionStructure
     *
     * @return self
     */
    public function addTypeInterventionStructure(TypeInterventionStructure $typeInterventionStructure)
    {
        $this->typeInterventionStructure[] = $typeInterventionStructure;

        return $this;
    }



    /**
     * Remove typeInterventionStructure
     *
     * @param TypeInterventionStructure $typeInterventionStructure
     *
     * @return self
     */
    public function removeTypeInterventionStructure(TypeInterventionStructure $typeInterventionStructure)
    {
        $this->typeInterventionStructure->removeElement($typeInterventionStructure);

        return $this;
    }



    /**
     * Get typeInterventionStructure
     *
     * @return \Doctrine\Common\Collections\Collection|TypeInterventionStructure
     */
    public function getTypeInterventionStructure()
    {
        return $this->typeInterventionStructure;
    }



    /**
     * Get typeInterventionStructure
     *
     * @return \Doctrine\Common\Collections\Collection|TypeInterventionStructure
     */
    public function getTypeInterventionStructureValides(Annee $annee)
    {
        return $this->typeInterventionStructure->filter(function (TypeInterventionStructure $tis) use ($annee) {
            return $tis->isValide($annee);
        });
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeIntervention
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return TypeIntervention
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    public function getTauxHetdService()
    {
        return $this->tauxHetdService;
    }



    public function getTauxHetdComplementaire()
    {
        return $this->tauxHetdComplementaire;
    }



    public function setTauxHetdService($tauxHetdService)
    {
        $this->tauxHetdService = $tauxHetdService;

        return $this;
    }



    public function setTauxHetdComplementaire($tauxHetdComplementaire)
    {
        $this->tauxHetdComplementaire = $tauxHetdComplementaire;

        return $this;
    }



    public function getResourceId(): string
    {
        return self::class;
    }



    /**
     * Get typeInterventionStatut
     *
     * @return \Doctrine\Common\Collections\Collection|TypeInterventionStatut
     */
    public function getTypeInterventionStatut(?Annee $annee = null)
    {
        if ($annee) {
            return $this->typeInterventionStatut->filter(function (TypeInterventionStatut $tis) use ($annee) {
                return $tis->getAnnee() == $annee;
            });
        } else {
            return $this->typeInterventionStatut;
        }
    }
}

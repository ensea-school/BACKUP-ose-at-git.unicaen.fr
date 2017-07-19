<?php

namespace Application\Entity\Db;

use Application\Service\Annee;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeIntervention
 */
class TypeIntervention implements HistoriqueAwareInterface
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
     * anneeDebutId
     *
     * @var Annee
     */
    private $anneeDebutId;

    /**
     * anneeFinId
     *
     * @var Annee
     */
    private $anneeFinId;

    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typeInterventionStructure;

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
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->typeInterventionStructure = new \Doctrine\Common\Collections\ArrayCollection();
    }



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



    public function isVisible(Structure $structure = null, Annee $annee = null)
    {
        if ($structure && $annee){
            $lst = $this->getTypeInterventionStructure($structure, $annee);
            if ($lst->count() == 1){
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
     * @return Annee
     */
    public function getAnneeDebutId()
    {
        return $this->anneeDebutId;
    }

    /**
     * @param Annee $anneeDebutId
     *
     * @return TypeIntervention
     */
    public function setAnneeDebutId($anneeDebutId)
    {
        $this->anneeDebutId = $anneeDebutId;

        return $this;
    }

    /**
     * @return Annee
     */
    public function getAnneeFinId()
    {
        return $this->anneeFinId;
    }

    /**
     * @param Annee $anneeFinId
     *
     * @return TypeIntervention
     */
    public function setAnneeFinId($anneeFinId)
    {
        $this->anneeFinId = $anneeFinId;

        return $this;
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
    public function getTypeInterventionStructure(Structure $structure = null, Annee $annee = null)
    {
        if ($structure) {
            $tis = $this->typeInterventionStructure->filter(function (TypeInterventionStructure $t) use ($structure) {
                return $t->getStructure() === $structure && $t->estNonHistorise();
            });

            if ($annee) {
                $tis = $tis->filter(function (TypeInterventionStructure $t) use ($annee) {
                    $aDeb = $t->getAnneeDebut() ? $t->getAnneeDebut()->getId() : 0;
                    $aFin = $t->getAnneeFin() ? $t->getAnneeFin()->getId() : 9999999;
                    $aId = $annee->getId();

                    return $aDeb <= $aId && $aId <= $aFin;
                });
            }

            return $tis;
        }

        return $this->typeInterventionStructure;
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

}

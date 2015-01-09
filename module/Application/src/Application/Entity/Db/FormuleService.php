<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleService
 */
class FormuleService
{
    /**
     * @var float
     */
    private $ponderationServiceCompl;

    /**
     * @var float
     */
    private $ponderationServiceDu;

    /**
     * @var float
     */
    private $tauxFa;

    /**
     * @var float
     */
    private $tauxFc;

    /**
     * @var float
     */
    private $tauxFi;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     *
     * @var Structure
     */
    private $structureAff;

    /**
     *
     * @var Structure
     */
    private $structureEns;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleVolumeHoraire;

    /**
     * Get ponderationServiceCompl
     *
     * @return float 
     */
    public function getPonderationServiceCompl()
    {
        return $this->ponderationServiceCompl;
    }

    /**
     * Get ponderationServiceDu
     *
     * @return float 
     */
    public function getPonderationServiceDu()
    {
        return $this->ponderationServiceDu;
    }

    /**
     * Get tauxFa
     *
     * @return float 
     */
    public function getTauxFa()
    {
        return $this->tauxFa;
    }

    /**
     * Get tauxFc
     *
     * @return float 
     */
    public function getTauxFc()
    {
        return $this->tauxFc;
    }

    /**
     * Get tauxFi
     *
     * @return float 
     */
    public function getTauxFi()
    {
        return $this->tauxFi;
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
     * Get service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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
     * Get structure d'affectation
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructureAff()
    {
        return $this->structureAff;
    }

    /**
     * Get structure d'enseignement
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructureEns()
    {
        return $this->structureEns;
    }

    /**
     * Get formuleVolumeHoraire
     *
     * @param TypeIntervention  $typeIntervention
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param EtatVolumeHoraire $etatVolumehoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleVolumeHoraire( TypeIntervention $typeIntervention=null, TypeVolumeHoraire $typeVolumeHoraire=null, EtatVolumeHoraire $etatVolumehoraire=null )
    {
        $filter = function( FormuleVolumeHoraire $formuleVolumeHoraire ) use ($typeIntervention, $typeVolumeHoraire, $etatVolumehoraire) {
            if ($typeIntervention && $typeIntervention !== $formuleVolumeHoraire->getTypeIntervention()) {
                return false;
            }
            if ($typeVolumeHoraire && $typeVolumeHoraire !== $formuleVolumeHoraire->getTypeVolumeHoraire()) {
                return false;
            }
            if ($etatVolumehoraire && $etatVolumehoraire->getOrdre() > $formuleVolumeHoraire->getEtatVolumeHoraire()->getOrdre()) {
                return false;
            }
            return true;
        };
        return $this->formuleVolumeHoraire->filter($filter);
    }

    /**
     * Get formuleVolumeHoraire
     *
     * @param TypeIntervention  $typeIntervention
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param EtatVolumeHoraire $etatVolumehoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHeures( TypeIntervention $typeIntervention=null, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumehoraire )
    {
        $heures = 0;
        $formuleVolumehoraire = $this->getFormuleVolumeHoraire($typeIntervention, $typeVolumeHoraire, $etatVolumehoraire);
        foreach( $formuleVolumehoraire as $fvh ){
            $heures += $fvh->getHeures();
        }
        return $heures;
    }
}

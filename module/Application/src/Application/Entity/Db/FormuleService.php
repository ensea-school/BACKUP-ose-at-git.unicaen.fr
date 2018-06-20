<?php

namespace Application\Entity\Db;

/**
 * FormuleService
 */
class FormuleService
{
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
     * @var float
     */
    private $ponderationServiceDu;

    /**
     * @var float
     */
    private $ponderationServiceCompl;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\FormuleIntervenant
     */
    private $formuleIntervenant;

    /**
     * @var bool
     */
    private $serviceStatutaire = true;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleVolumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @param TypeHeures $typeHeures
     *
     * @return float
     * @throws \LogicException
     */
    public function getTaux(TypeHeures $typeHeures)
    {
        switch ($typeHeures->getCode()) {
            case TypeHeures::FI:
                return $this->getTauxFi();
            case TypeHeures::FA:
                return $this->getTauxFa();
            case TypeHeures::FC:
                return $this->getTauxFc();
        }
        throw new \LogicException('Le type d\'heures transmis n\'est pas correct');
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
     * Get ponderationServiceCompl
     *
     * @return float
     */
    public function getPonderationServiceCompl()
    {
        return $this->ponderationServiceCompl;
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
     * Get formuleVolumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null, TypeIntervention $typeIntervention = null)
    {
        $filter = function (FormuleVolumeHoraire $formuleVolumeHoraire) use ($typeVolumeHoraire, $etatVolumeHoraire, $typeIntervention) {
            if ($typeVolumeHoraire && $typeVolumeHoraire !== $formuleVolumeHoraire->getTypeVolumeHoraire()) {
                return false;
            }
            if ($etatVolumeHoraire && $etatVolumeHoraire->getOrdre() > $formuleVolumeHoraire->getEtatVolumeHoraire()->getOrdre()) {
                return false;
            }
            if ($typeIntervention && $typeIntervention !== $formuleVolumeHoraire->getTypeIntervention()) {
                return false;
            }

            return true;
        };

        return $this->formuleVolumeHoraire->filter($filter);
    }



    public function getHeures(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null, TypeIntervention $typeIntervention = null)
    {
        $heures = 0;
        $vhs    = $this->getFormuleVolumeHoraire($typeVolumeHoraire, $etatVolumeHoraire, $typeIntervention);
        foreach ($vhs as $vh) {
            /* @var $vh FormuleVolumeHoraire */
            $ok = true;
            if ($ok && $typeVolumeHoraire !== null && $vh->getTypeVolumeHoraire() !== $typeVolumeHoraire) $ok = false;
            if ($ok && $etatVolumeHoraire !== null && $vh->getEtatVolumeHoraire() !== $etatVolumeHoraire) $ok = false;
            if ($ok && $typeIntervention !== null && $vh->getTypeIntervention() !== $typeIntervention) $ok = false;
            if ($ok) $heures += $vh->getHeures();
        }

        return $heures;
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
     * Get formuleIntervenant
     *
     * @return \Application\Entity\Db\FormuleIntervenant
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }



    /**
     * @return bool
     */
    public function isServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    /**
     * @param bool $serviceStatutaire
     *
     * @return FormuleService
     */
    public function setServiceStatutaire(bool $serviceStatutaire): FormuleService
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }

}

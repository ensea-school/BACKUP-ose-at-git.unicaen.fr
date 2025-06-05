<?php

namespace Formule\Entity;

use Application\Entity\Db\Annee;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Formule\Model\Arrondisseur\Ligne;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

class FormuleIntervenant
{
    const ARRONDISSEUR_NO      = 0;
    const ARRONDISSEUR_MINIMAL = 1;
    const ARRONDISSEUR_FULL    = 2;
    const ARRONDISSEUR_CUSTOM  = 3;

    // Identifiants
    protected ?int               $id                = null;
    protected ?Annee             $annee             = null;
    protected ?TypeVolumeHoraire $typeVolumeHoraire = null;
    protected ?EtatVolumeHoraire $etatVolumeHoraire = null;

    // Paramètres globaux
    protected ?TypeIntervenant $typeIntervenant            = null;
    protected ?string          $structureCode              = null;
    protected float            $heuresServiceStatutaire    = 0.0;
    protected float            $heuresServiceModifie       = 0.0;
    protected bool             $depassementServiceDuSansHC = false;

    // Paramètres spécifiques
    protected ?string $param1 = null;
    protected ?string $param2 = null;
    protected ?string $param3 = null;
    protected ?string $param4 = null;
    protected ?string $param5 = null;

    // Résultats
    protected float $serviceDu = 0.0;

    protected int   $arrondisseur      = self::ARRONDISSEUR_FULL;
    protected ?Ligne $arrondisseurTrace = null;

    /**
     * Volumes horaires
     *
     * @var Collection|FormuleVolumeHoraire[]
     */
    protected Collection $volumesHoraires;



    public function __construct()
    {
        $this->volumesHoraires = new ArrayCollection();
    }



    /**
     * @return Collection|FormuleVolumeHoraire[]
     */
    public function getVolumesHoraires(): Collection
    {
        return $this->volumesHoraires;
    }



    public function addVolumeHoraire(FormuleVolumeHoraire $volumeHoraire): FormuleIntervenant
    {
        $this->volumesHoraires->add($volumeHoraire);
        if (empty($volumeHoraire->getFormuleIntervenant())) {
            $volumeHoraire->setFormuleIntervenant($this);
        }
        return $this;
    }



    public function removeVolumeHoraire(string|FormuleVolumeHoraire $volumeHoraire): FormuleIntervenant
    {
        if ($volumeHoraire instanceof FormuleVolumeHoraire) {
            $this->volumesHoraires->removeElement($volumeHoraire);
            $volumeHoraire->setFormuleIntervenant(null);
        } else {
            $this->volumesHoraires->remove($volumeHoraire);
        }
        return $this;
    }



    public function getHeuresServiceFi(): float
    {
        $serviceFi = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $serviceFi += $volumesHoraire->getHeuresServiceFi();
        }

        return $serviceFi;
    }



    public function getHeuresServiceFa(): float
    {
        $serviceFa = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $serviceFa += $volumesHoraire->getHeuresServiceFa();
        }

        return $serviceFa;
    }



    public function getHeuresServiceFc(): float
    {
        $serviceFc = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $serviceFc += $volumesHoraire->getHeuresServiceFc();
        }

        return $serviceFc;
    }



    public function getHeuresServiceReferentiel(): float
    {
        $serviceReferentiel = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $serviceReferentiel += $volumesHoraire->getHeuresServiceReferentiel();
        }

        return $serviceReferentiel;
    }



    public function getHeuresNonPayableFi(): float
    {
        $nonPayableFi = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $nonPayableFi += $volumesHoraire->getHeuresNonPayableFi();
        }

        return $nonPayableFi;
    }



    public function getHeuresNonPayableFa(): float
    {
        $nonPayableFa = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $nonPayableFa += $volumesHoraire->getHeuresNonPayableFa();
        }

        return $nonPayableFa;
    }



    public function getHeuresNonPayableFc(): float
    {
        $nonPayableFc = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $nonPayableFc += $volumesHoraire->getHeuresNonPayableFc();
        }

        return $nonPayableFc;
    }



    public function getHeuresNonPayableReferentiel(): float
    {
        $nonPayableReferentiel = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $nonPayableReferentiel += $volumesHoraire->getHeuresNonPayableReferentiel();
        }

        return $nonPayableReferentiel;
    }



    public function getHeuresComplFi(): float
    {
        $heuresComplFi = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $heuresComplFi += $volumesHoraire->getHeuresComplFi();
        }

        return $heuresComplFi;
    }



    public function getHeuresComplFa(): float
    {
        $heuresComplFa = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $heuresComplFa += $volumesHoraire->getHeuresComplFa();
        }

        return $heuresComplFa;
    }



    public function getHeuresComplFc(): float
    {
        $heuresComplFc = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $heuresComplFc += $volumesHoraire->getHeuresComplFc();
        }

        return $heuresComplFc;
    }



    public function getHeuresPrimes(): float
    {
        $heuresPrimes = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $heuresPrimes += $volumesHoraire->getHeuresPrimes();
        }

        return $heuresPrimes;
    }



    public function getHeuresComplReferentiel(): float
    {
        $heuresComplReferentiel = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $heuresComplReferentiel += $volumesHoraire->getHeuresComplReferentiel();
        }

        return $heuresComplReferentiel;
    }



    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->volumesHoraires as $volumesHoraire) {
            $total += $volumesHoraire->getTotal();
        }

        return $total;
    }



    public function getServiceDu(): float
    {
        return $this->getHeuresServiceStatutaire() + $this->getHeuresServiceModifie();
    }



    /***********************************/
    /* Accésseurs générés par PhpStorm */
    /***********************************/


    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): FormuleIntervenant
    {
        $this->id = $id;
        return $this;
    }



    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }



    public function setAnnee(?Annee $annee): FormuleIntervenant
    {
        $this->annee = $annee;
        return $this;
    }



    public function getTypeVolumeHoraire(): ?TypeVolumeHoraire
    {
        return $this->typeVolumeHoraire;
    }



    public function setTypeVolumeHoraire(?TypeVolumeHoraire $typeVolumeHoraire): FormuleIntervenant
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        return $this;
    }



    public function getEtatVolumeHoraire(): ?EtatVolumeHoraire
    {
        return $this->etatVolumeHoraire;
    }



    public function setEtatVolumeHoraire(?EtatVolumeHoraire $etatVolumeHoraire): FormuleIntervenant
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;
        return $this;
    }



    public function getTypeIntervenant(): ?TypeIntervenant
    {
        return $this->typeIntervenant;
    }



    public function setTypeIntervenant(?TypeIntervenant $typeIntervenant): FormuleIntervenant
    {
        $this->typeIntervenant = $typeIntervenant;
        return $this;
    }



    public function getStructureCode(): ?string
    {
        return $this->structureCode;
    }



    public function setStructureCode(?string $structureCode): FormuleIntervenant
    {
        $this->structureCode = $structureCode;
        return $this;
    }



    public function getHeuresServiceStatutaire(): float
    {
        return $this->heuresServiceStatutaire;
    }



    public function setHeuresServiceStatutaire(float $heuresServiceStatutaire): FormuleIntervenant
    {
        $this->heuresServiceStatutaire = $heuresServiceStatutaire;
        return $this;
    }



    public function getHeuresServiceModifie(): float
    {
        return $this->heuresServiceModifie;
    }



    public function setHeuresServiceModifie(float $heuresServiceModifie): FormuleIntervenant
    {
        $this->heuresServiceModifie = $heuresServiceModifie;
        return $this;
    }



    public function isDepassementServiceDuSansHC(): bool
    {
        return $this->depassementServiceDuSansHC;
    }



    public function setDepassementServiceDuSansHC(bool $depassementServiceDuSansHC): FormuleIntervenant
    {
        $this->depassementServiceDuSansHC = $depassementServiceDuSansHC;
        return $this;
    }



    public function getParam1(): ?string
    {
        return $this->param1;
    }



    public function setParam1(?string $param1): FormuleIntervenant
    {
        $this->param1 = $param1;
        return $this;
    }



    public function getParam2(): ?string
    {
        return $this->param2;
    }



    public function setParam2(?string $param2): FormuleIntervenant
    {
        $this->param2 = $param2;
        return $this;
    }



    public function getParam3(): ?string
    {
        return $this->param3;
    }



    public function setParam3(?string $param3): FormuleIntervenant
    {
        $this->param3 = $param3;
        return $this;
    }



    public function getParam4(): ?string
    {
        return $this->param4;
    }



    public function setParam4(?string $param4): FormuleIntervenant
    {
        $this->param4 = $param4;
        return $this;
    }



    public function getParam5(): ?string
    {
        return $this->param5;
    }



    public function setParam5(?string $param5): FormuleIntervenant
    {
        $this->param5 = $param5;
        return $this;
    }



    public function getArrondisseurTrace(): ?Ligne
    {
        return $this->arrondisseurTrace;
    }



    public function setArrondisseurTrace(?Ligne $arrondisseurTrace): FormuleIntervenant
    {
        $this->arrondisseurTrace = $arrondisseurTrace;
        return $this;
    }



    public function getArrondisseur(): int
    {
        return $this->arrondisseur;
    }



    public function setArrondisseur(int $arrondisseur): FormuleIntervenant
    {
        $this->arrondisseur = $arrondisseur;
        return $this;
    }

}
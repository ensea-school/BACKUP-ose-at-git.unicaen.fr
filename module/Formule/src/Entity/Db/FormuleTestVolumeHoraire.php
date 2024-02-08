<?php

namespace Formule\Entity\Db;

use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;


class FormuleTestVolumeHoraire extends FormuleVolumeHoraire
{
    protected bool $referentiel = false;

    protected float $heuresAttenduesServiceFi = 0.0;
    protected float $heuresAttenduesServiceFa = 0.0;
    protected float $heuresAttenduesServiceFc = 0.0;
    protected float $heuresAttenduesServiceReferentiel = 0.0;

    protected float $heuresAttenduesNonPayableFi = 0.0;
    protected float $heuresAttenduesNonPayableFa = 0.0;
    protected float $heuresAttenduesNonPayableFc = 0.0;
    protected float $heuresAttenduesNonPayableReferentiel = 0.0;

    protected float $heuresAttenduesComplFi = 0.0;
    protected float $heuresAttenduesComplFa = 0.0;
    protected float $heuresAttenduesComplFc = 0.0;
    protected float $heuresAttenduesComplFcMajorees = 0.0;
    protected float $heuresAttenduesComplReferentiel = 0.0;



    public function getFormuleIntervenant(): ?FormuleTestIntervenant
    {
        return $this->formuleIntervenant;
    }



    public function setFormuleIntervenant(?FormuleIntervenant $formuleIntervenant): FormuleVolumeHoraire
    {
        if (!$formuleIntervenant instanceof FormuleTestIntervenant) {
            throw new \Exception('Classe incompatible : un FormuleTestIntervenant doit être fourni obligatoirement');
        }
        $this->formuleIntervenant = $formuleIntervenant;
        return $this;
    }



    public function getVolumeHoraire(): VolumeHoraire|int|null
    {
        return $this->referentiel ? null : 1;
    }



    public function setVolumeHoraire(VolumeHoraire|int|null $volumeHoraire): FormuleVolumeHoraire
    {
        $this->referentiel = (bool)$volumeHoraire;
        return $this;
    }



    public function getVolumeHoraireReferentiel(): VolumeHoraireReferentiel|int|null
    {
        return $this->referentiel ? 1 : null;
    }



    public function setVolumeHoraireReferentiel(VolumeHoraireReferentiel|int|null $volumeHoraireReferentiel): FormuleVolumeHoraire
    {
        $this->referentiel = !(bool)$volumeHoraireReferentiel;
        return $this;
    }



    public function getService(): Service|int|null
    {
        return $this->referentiel ? null : 1;
    }



    public function setService(Service|int|null $service): FormuleVolumeHoraire
    {
        $this->referentiel = (bool)$service;
        return $this;
    }



    public function getServiceReferentiel(): int|ServiceReferentiel|null
    {
        return $this->referentiel ? 1 : null;
    }



    public function setServiceReferentiel(int|ServiceReferentiel|null $serviceReferentiel): FormuleVolumeHoraire
    {
        $this->referentiel = !(bool)$serviceReferentiel;
        return $this;
    }



    public function setReferentiel(bool $referentiel): FormuleTestVolumeHoraire
    {
        if ($referentiel) {
            $this->typeInterventionCode = null;
        }
        $this->referentiel = $referentiel;
        return $this;
    }



    public function setTypeInterventionCode(?string $typeInterventionCode): FormuleTestVolumeHoraire
    {
        if (!empty($typeInterventionCode)) {
            $this->referentiel = false;
        }
        parent::setTypeInterventionCode($typeInterventionCode);
        return $this;
    }



    public function isStructureUniv(): bool
    {
        return $this->structureCode == FormuleTestIntervenant::STRUCTURE_UNIV;
    }



    public function setStructureUniv(bool $structureUniv): FormuleVolumeHoraire
    {
        $this->structureCode = FormuleTestIntervenant::STRUCTURE_UNIV;
        return $this;
    }



    public function isStructureExterieur(): bool
    {
        return $this->structureCode == FormuleTestIntervenant::STRUCTURE_EXTERIEUR;
    }



    public function setStructureExterieur(bool $structureExterieur): FormuleVolumeHoraire
    {
        $this->structureCode = FormuleTestIntervenant::STRUCTURE_EXTERIEUR;
        return $this;
    }



    public function getTauxServiceDu(): float
    {
        /** @var FormuleTestIntervenant $testIntervenant */
        $testIntervenant = $this->getFormuleIntervenant();

        return $testIntervenant->getTauxServiceDu($this->getTypeInterventionCode());
    }



    public function setTauxServiceDu(float $tauxServiceDu): FormuleVolumeHoraire
    {
        throw new \Exception('Il est impossible de modifier ce taux ici');
    }



    public function getTauxServiceCompl(): float
    {
        /** @var FormuleTestIntervenant $testIntervenant */
        $testIntervenant = $this->getFormuleIntervenant();

        return $testIntervenant->getTauxTpServiceCompl($this->getTypeInterventionCode());
    }



    public function setTauxServiceCompl(float $tauxServiceCompl): FormuleVolumeHoraire
    {
        throw new \Exception('Il est impossible de modifier ce taux ici');
    }



    /***********************************/
    /* Accésseurs générés par PhpStorm */
    /***********************************/


    public function isReferentiel(): bool
    {
        return $this->referentiel;
    }



    public function getHeuresAttenduesServiceFi(): float
    {
        return $this->heuresAttenduesServiceFi;
    }



    public function setHeuresAttenduesServiceFi(float $heuresAttenduesServiceFi): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesServiceFi = $heuresAttenduesServiceFi;
        return $this;
    }



    public function getHeuresAttenduesServiceFa(): float
    {
        return $this->heuresAttenduesServiceFa;
    }



    public function setHeuresAttenduesServiceFa(float $heuresAttenduesServiceFa): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesServiceFa = $heuresAttenduesServiceFa;
        return $this;
    }



    public function getHeuresAttenduesServiceFc(): float
    {
        return $this->heuresAttenduesServiceFc;
    }



    public function setHeuresAttenduesServiceFc(float $heuresAttenduesServiceFc): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesServiceFc = $heuresAttenduesServiceFc;
        return $this;
    }



    public function getHeuresAttenduesServiceReferentiel(): float
    {
        return $this->heuresAttenduesServiceReferentiel;
    }



    public function setHeuresAttenduesServiceReferentiel(float $heuresAttenduesServiceReferentiel): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesServiceReferentiel = $heuresAttenduesServiceReferentiel;
        return $this;
    }



    public function getHeuresAttenduesNonPayableFi(): float
    {
        return $this->heuresAttenduesNonPayableFi;
    }



    public function setHeuresAttenduesNonPayableFi(float $heuresAttenduesNonPayableFi): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesNonPayableFi = $heuresAttenduesNonPayableFi;
        return $this;
    }



    public function getHeuresAttenduesNonPayableFa(): float
    {
        return $this->heuresAttenduesNonPayableFa;
    }



    public function setHeuresAttenduesNonPayableFa(float $heuresAttenduesNonPayableFa): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesNonPayableFa = $heuresAttenduesNonPayableFa;
        return $this;
    }



    public function getHeuresAttenduesNonPayableFc(): float
    {
        return $this->heuresAttenduesNonPayableFc;
    }



    public function setHeuresAttenduesNonPayableFc(float $heuresAttenduesNonPayableFc): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesNonPayableFc = $heuresAttenduesNonPayableFc;
        return $this;
    }



    public function getHeuresAttenduesNonPayableReferentiel(): float
    {
        return $this->heuresAttenduesNonPayableReferentiel;
    }



    public function setHeuresAttenduesNonPayableReferentiel(float $heuresAttenduesNonPayableReferentiel): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesNonPayableReferentiel = $heuresAttenduesNonPayableReferentiel;
        return $this;
    }



    public function getHeuresAttenduesComplFi(): float
    {
        return $this->heuresAttenduesComplFi;
    }



    public function setHeuresAttenduesComplFi(float $heuresAttenduesComplFi): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesComplFi = $heuresAttenduesComplFi;
        return $this;
    }



    public function getHeuresAttenduesComplFa(): float
    {
        return $this->heuresAttenduesComplFa;
    }



    public function setHeuresAttenduesComplFa(float $heuresAttenduesComplFa): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesComplFa = $heuresAttenduesComplFa;
        return $this;
    }



    public function getHeuresAttenduesComplFc(): float
    {
        return $this->heuresAttenduesComplFc;
    }



    public function setHeuresAttenduesComplFc(float $heuresAttenduesComplFc): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesComplFc = $heuresAttenduesComplFc;
        return $this;
    }



    public function getHeuresAttenduesComplFcMajorees(): float
    {
        return $this->heuresAttenduesComplFcMajorees;
    }



    public function setHeuresAttenduesComplFcMajorees(float $heuresAttenduesComplFcMajorees): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesComplFcMajorees = $heuresAttenduesComplFcMajorees;
        return $this;
    }



    public function getHeuresAttenduesComplReferentiel(): float
    {
        return $this->heuresAttenduesComplReferentiel;
    }



    public function setHeuresAttenduesComplReferentiel(float $heuresAttenduesComplReferentiel): FormuleTestVolumeHoraire
    {
        $this->heuresAttenduesComplReferentiel = $heuresAttenduesComplReferentiel;
        return $this;
    }


}

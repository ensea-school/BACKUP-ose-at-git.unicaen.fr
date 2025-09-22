<?php

namespace Workflow\Entity\Db;

use Administration\Interfaces\ParametreEntityInterface;
use Administration\Traits\ParametreEntityTrait;
use Application\Entity\Db\Perimetre;
use Intervenant\Entity\Db\TypeIntervenant;

class WorkflowEtapeDependance implements ParametreEntityInterface
{
    use ParametreEntityTrait;

    // Si débutée alors l'étape devra être franchie à plus de 0%
    // Par exemple au moins 1h de service devra être saisie.
    const AVANCEMENT_DEBUTE = 1;

    // Si terminée partiellement : l'étape peut avoir été franchie à 100 sur au moins un élément
    // Par exemple si on a plusieurs contrats ou avenants, si l'un d'entre eux est terminé alors ça passe.
    const AVANCEMENT_TERMINE_PARTIELLEMENT = 2;

    // Si terminée intégralement : l'ensemble des éléments de l'étape doivent avoir été franchis
    // Par exemple si on a plusieurs contrats et avenants, tous doivent être terminés.
    const AVANCEMENT_TERMINE_INTEGRALEMENT = 3;


    protected ?WorkflowEtape $etapeSuivante = null;

    protected ?WorkflowEtape $etapePrecedante = null;

    protected bool $active = true;

    protected ?TypeIntervenant $typeIntervenant = null;

    protected ?Perimetre $perimetre = null;

    protected int $avancement = self::AVANCEMENT_TERMINE_INTEGRALEMENT;



    public function getEtapeSuivante(): ?WorkflowEtape
    {
        return $this->etapeSuivante;
    }



    public function setEtapeSuivante(WorkflowEtape $etapeSuivante): WorkflowEtapeDependance
    {
        $this->etapeSuivante = $etapeSuivante;
        return $this;
    }



    public function getEtapePrecedante(): ?WorkflowEtape
    {
        return $this->etapePrecedante;
    }



    public function setEtapePrecedante(WorkflowEtape $etapePrecedante): WorkflowEtapeDependance
    {
        $this->etapePrecedante = $etapePrecedante;
        return $this;
    }



    public function isActive(): bool
    {
        return $this->active;
    }



    public function setActive(bool $active): WorkflowEtapeDependance
    {
        $this->active = $active;
        return $this;
    }



    public function getTypeIntervenant(): ?TypeIntervenant
    {
        return $this->typeIntervenant;
    }



    public function setTypeIntervenant(?TypeIntervenant $typeIntervenant): WorkflowEtapeDependance
    {
        $this->typeIntervenant = $typeIntervenant;
        return $this;
    }



    public function getPerimetre(): ?Perimetre
    {
        return $this->perimetre;
    }



    public function setPerimetre(?Perimetre $perimetre): WorkflowEtapeDependance
    {
        $this->perimetre = $perimetre;
        return $this;
    }



    public function getAvancement(): int
    {
        return $this->avancement;
    }



    public function setAvancement(int $avancement): WorkflowEtapeDependance
    {
        $this->avancement = $avancement;
        return $this;
    }



    public function getAvancementLibelle(): string
    {
        $avancements = $this->etapePrecedante?->getAvancements() ?: [];

        return $avancements[$this->avancement] ?? match ($this->avancement) {
            self::AVANCEMENT_DEBUTE                => 'Débuté',
            self::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Partiel',
            self::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Intégral',
            default                                => 'Aucune règle fixée',
        };
    }



    public function isAvancementDebute(): bool
    {
        return $this->avancement == self::AVANCEMENT_DEBUTE;
    }



    public function isAvancementPartiel(): bool
    {
        return $this->avancement == self::AVANCEMENT_TERMINE_PARTIELLEMENT;
    }



    public function isAvancementIntegral(): bool
    {
        return $this->avancement == self::AVANCEMENT_TERMINE_INTEGRALEMENT;
    }
}

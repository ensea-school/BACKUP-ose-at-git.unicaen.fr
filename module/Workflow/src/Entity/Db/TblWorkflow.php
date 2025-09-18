<?php

namespace Workflow\Entity\Db;

use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;

class TblWorkflow
{
    private int           $id;
    private Intervenant   $intervenant;
    private WorkflowEtape $etape;
    private ?Structure    $structure;
    private bool          $atteignable;
    private float         $realisation;
    private float         $objectif;
    private float         $partiel;



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function setIntervenant(Intervenant $intervenant): TblWorkflow
    {
        $this->intervenant = $intervenant;
        return $this;
    }



    public function getEtape(): WorkflowEtape
    {
        return $this->etape;
    }



    public function setEtape(WorkflowEtape $etape): TblWorkflow
    {
        $this->etape = $etape;
        return $this;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function setStructure(?Structure $structure): TblWorkflow
    {
        $this->structure = $structure;
        return $this;
    }



    public function isAtteignable(): bool
    {
        return $this->atteignable;
    }



    public function setAtteignable(bool $ateignable): TblWorkflow
    {
        $this->atteignable = $atteignable;
        return $this;
    }



    public function getRealisation(): float
    {
        return $this->realisation;
    }



    public function setRealisation(float $realisation): TblWorkflow
    {
        $this->realisation = $realisation;
        return $this;
    }



    public function getObjectif(): float
    {
        return $this->objectif;
    }



    public function setObjectif(float $objectif): TblWorkflow
    {
        $this->objectif = $objectif;
        return $this;
    }



    public function getPartiel(): float
    {
        return $this->partiel;
    }



    public function setPartiel(float $partiel): TblWorkflow
    {
        $this->partiel = $partiel;
        return $this;
    }
}


<?php

namespace Workflow\Entity\Db;

use Application\Entity\Db\Perimetre;

class WorkflowEtape
{
    private ?int $id = null;

    private ?string $code = null;

    private int $ordre = 1;

    private ?Perimetre $perimetre = null;

    private ?string $route = null;

    private ?string $routeIntervenant = null;

    private ?string $libelleIntervenant = null;

    private ?string $libelleAutres = null;

    private ?string $descNonFranchie = null;

    private ?string $descSansObjectif = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): WorkflowEtape
    {
        $this->code = $code;
        return $this;
    }



    public function getOrdre(): int
    {
        return $this->ordre;
    }



    public function setOrdre(int $ordre): WorkflowEtape
    {
        $this->ordre = $ordre;
        return $this;
    }



    public function getPerimetre(): ?Perimetre
    {
        return $this->perimetre;
    }



    public function setPerimetre(?Perimetre $perimetre): WorkflowEtape
    {
        $this->perimetre = $perimetre;
        return $this;
    }



    public function getRoute(): ?string
    {
        return $this->route;
    }



    public function setRoute(?string $route): WorkflowEtape
    {
        $this->route = $route;
        return $this;
    }



    public function getRouteIntervenant(): ?string
    {
        return $this->routeIntervenant;
    }



    public function setRouteIntervenant(?string $routeIntervenant): WorkflowEtape
    {
        $this->routeIntervenant = $routeIntervenant;
        return $this;
    }



    public function getLibelleIntervenant(): ?string
    {
        return $this->libelleIntervenant;
    }



    public function setLibelleIntervenant(?string $libelleIntervenant): WorkflowEtape
    {
        $this->libelleIntervenant = $libelleIntervenant;
        return $this;
    }



    public function getLibelleAutres(): ?string
    {
        return $this->libelleAutres;
    }



    public function setLibelleAutres(?string $libelleAutres): WorkflowEtape
    {
        $this->libelleAutres = $libelleAutres;
        return $this;
    }



    public function getDescNonFranchie(): ?string
    {
        return $this->descNonFranchie;
    }



    public function setDescNonFranchie(?string $descNonFranchie): WorkflowEtape
    {
        $this->descNonFranchie = $descNonFranchie;
        return $this;
    }



    public function getDescSansObjectif(): ?string
    {
        return $this->descSansObjectif;
    }



    public function setDescSansObjectif(?string $descSansObjectif): WorkflowEtape
    {
        $this->descSansObjectif = $descSansObjectif;
        return $this;
    }

}
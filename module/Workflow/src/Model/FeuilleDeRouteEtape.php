<?php

namespace Workflow\Model;


use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowService;

class FeuilleDeRouteEtape
{
    private WorkflowService $service;

    private FeuilleDeRoute $feuilleDeRoute;

    /**
     * @var array|FeuilleDeRouteEtape[]
     */
    public array $structures = [];

    public int           $numero;
    public WorkflowEtape $workflowEtape;
    public string        $libelle;
    public ?string       $url         = null;
    public bool          $atteignable = true;
    public float         $objectif    = 1.0;
    public float         $realisation = 0.0;
    public array         $whyNonAtteignable = [];



    public function __construct(FeuilleDeRoute $feuilleDeRoute, WorkflowService $service)
    {
        $this->feuilleDeRoute = $feuilleDeRoute;
        $this->service        = $service;
    }



    public function getRealisationPourc(): int
    {
        $objectif = max($this->objectif, $this->realisation, 1);

        return round(($this->realisation / $objectif) * 100, 0);
    }



    public function getCode(): string
    {
        return $this->workflowEtape->getCode();
    }



    public function isCourante(): bool
    {
        return $this === $this->feuilleDeRoute->getCourante();
    }



    public function isAllowed(): bool
    {
        return true;
    }
}
<?php

namespace Workflow\Model;


use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Authorize\Authorize;
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
    public ?string       $url               = null;
    public bool          $atteignable       = true;
    public float         $objectif          = 0.0;
    public float         $realisation       = 0.0;
    public array         $whyNonAtteignable = [];
    private Authorize    $authorize;



    public function __construct(FeuilleDeRoute $feuilleDeRoute, WorkflowService $service, WorkflowEtape $etape)
    {
        $this->feuilleDeRoute = $feuilleDeRoute;
        $this->service        = $service;
        $this->workflowEtape  = $etape;

        // Récupération en direct du service Authorize
        $this->authorize = Application::getInstance()->container()->get(Authorize::class);
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



    public function isFranchie(): bool
    {
        return $this->realisation >= $this->objectif;
    }



    public function isAllowed(): bool
    {
        return $this->atteignable || $this->realisation > 0;
    }



    public function isNavigable()
    {
        if (!$this->isAllowed()) {
            return false;
        }

        if ($route = $this->workflowEtape->getRoute()) {
            $params = [
                'intervenant' => $this->feuilleDeRoute->getIntervenant(),
            ];
            return $this->authorize->isAllowedRoute($route, $params);
        } else {
            return true;
        }
    }
}
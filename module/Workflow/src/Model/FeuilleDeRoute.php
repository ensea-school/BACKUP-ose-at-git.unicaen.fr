<?php

namespace Workflow\Model;

use Lieu\Entity\Db\Structure;
use Intervenant\Entity\Db\Intervenant;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowService;

class FeuilleDeRoute
{
    private WorkflowService $service;

    private Intervenant $intervenant;

    private ?Structure $structure = null;

    /**
     * @var array|WorkflowEtape[]
     */
    private array $workflowEtapes;

    /**
     * @var array|FeuilleDeRouteEtape[]
     */
    private array $fdr = [];

    private bool $builted = false;



    public function __construct(WorkflowService $service, Intervenant $intervenant, array $workflowEtapes)
    {
        $this->service        = $service;
        $this->intervenant    = $intervenant;
        $this->workflowEtapes = $workflowEtapes;
    }



    public function refresh(): void
    {
        $this->fdr     = [];
        $this->builted = false;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function setStructure(?Structure $structure): FeuilleDeRoute
    {
        $this->structure = $structure;
        $this->refresh();

        return $this;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    /**
     * Retourne la liste des étapes de la feuille de route
     *
     * @return array|FeuilleDeRouteEtape[]
     */
    public function getEtapes(): array
    {
        if (!$this->builted) {
            $this->build();
        }

        return $this->fdr;
    }



    public function get(string $etapeCode): ?FeuilleDeRouteEtape
    {
        if (!$this->builted) {
            $this->build();
        }

        if (array_key_exists($etapeCode, $this->fdrByName)) {
            return $this->fdr[$etapeCode];
        } else {
            return null;
        }
    }



    public function getCourante(): ?FeuilleDeRouteEtape
    {
        return null;
    }



    private function build(): void
    {
        $this->refresh();

        $sql       = "
        SELECT
          w.etape_code,
          w.structure_id,
          str.libelle_court structure_libelle,
          w.atteignable,
          w.objectif,
          w.partiel,
          w.realisation
        FROM
          tbl_workflow w
          JOIN workflow_etape we ON we.id = w.etape_id
          LEFT JOIN structure str ON str.id = w.structure_id        
        WHERE
          w.intervenant_id = :intervenant
        ORDER BY
          we.ordre
        ";
        $sqlParams = ['intervenant' => $this->intervenant->getId()];
        if ($this->structure) {
            $sql                    .= ' AND (w.structure_id = :structure OR w.structure_id IS NULL)';
            $sqlParams['structure'] = $this->structure->getId();
        }
        $stmt = $this->service->getBdd()->selectEach($sql, $sqlParams);

        while ($d = $stmt->next()) {
            mpg_lower($d);

            $etapeCode         = $d['etape_code'];
            $structureId       = (int)$d['structure_id'];
            $structureLiblelle = $d['structure_libelle'];
            $atteignable       = (bool)$d['atteignable'];
            $objectif          = (float)$d['objectif'];
            $partiel           = (float)$d['partiel'];
            $realisation       = (float)$d['realisation'];

            $etape = $this->workflowEtapes[$etapeCode];

            $this->buildEtape($etape, $structureId, $structureLiblelle, $atteignable, $objectif, $partiel, $realisation);
        }

        foreach ($this->fdr as $fdre) {
            if (count($fdre->structures) == 1) {
            //    $fdre->structures = []; // Pas de détail par structures s'il n'y en a qu'une
            }
        }

        $this->builted = true;
    }



    private function buildEtape(WorkflowEtape $etape, int $structureId, ?string $structureLibelle, bool $atteignable, float $objectif, float $partiel, float $realisation): void
    {
        $role        = $this->service->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $this->service->getServiceContext()->getIntervenant();

        if (!array_key_exists($etape->getCode(), $this->fdr)) {
            $fdre                = new FeuilleDeRouteEtape($this, $this->service);
            $fdre->workflowEtape = $etape;
            $fdre->numero        = count($this->fdr) + 1;
            $fdre->libelle       = $etape->getLibelle($role);
            if ($intervenant && !$role) {
                $fdre->url = $this->service->getUrl($etape->getRouteIntervenant() ?: $etape->getRoute(), ['intervenant' => $this->getIntervenant()->getId()]);
            } else {
                $fdre->url = $this->service->getUrl($etape->getRoute(), ['intervenant' => $this->getIntervenant()->getId()]);
            }
            $fdre->atteignable = $atteignable;
            $fdre->objectif    = $objectif;
            $fdre->realisation = $realisation;

            $this->fdr[$etape->getCode()] = $fdre;
        } else {
            $fdre = $this->fdr[$etape->getCode()];
        }

        if ($structureId) {
            if (!$this->getStructure() || $this->getStructure()->getId() == $structureId) {
                $fdres                          = new FeuilleDeRouteEtape($this, $this->service);
                $fdres->workflowEtape           = $etape;
                $fdres->numero                  = count($fdre->structures) + 1;
                $fdres->libelle                 = $structureLibelle;
                $fdres->url                     = null;
                $fdres->atteignable             = $atteignable;
                $fdres->objectif                = $objectif;
                $fdres->realisation             = $realisation;
                $fdre->structures[$structureId] = $fdres;
            }
        }
    }
}

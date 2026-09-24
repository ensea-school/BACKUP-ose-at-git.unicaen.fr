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

    private ?string $couranteCache = null;

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
        $this->couranteCache = null;
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

        if (array_key_exists($etapeCode, $this->fdr)) {
            return $this->fdr[$etapeCode];
        } else {
            return null;
        }
    }



    public function getCourante(): ?FeuilleDeRouteEtape
    {
        if (!$this->couranteCache) {
            $wfEtape = $this->getNext();
            if ($wfEtape){
                $this->couranteCache = $wfEtape->getCode();
            }
        }

        return $this->fdr[$this->couranteCache] ?? null;
    }



    public function getNext(string|WorkflowEtape|FeuilleDeRouteEtape|null $etape = null, bool $needAllowed = true): ?FeuilleDeRouteEtape
    {
        if (!$this->builted) {
            $this->build();
        }

        if ($etape instanceof WorkflowEtape) {
            $etape = $etape->getCode();
        }
        if ($etape instanceof FeuilleDeRouteEtape) {
            $etape = $etape->workflowEtape->getCode();
        }

        // Si l'étape n'est pas précisée, on est déjà dans la prochaine
        $isNext = $etape === null;

        foreach($this->fdr as $code => $wfEtape){
            if ($isNext){
                if ($wfEtape->isVisible() && $wfEtape->atteignable && !$wfEtape->isFranchie()){
                    if (!$needAllowed || $wfEtape->isAllowed()){
                        return $wfEtape;
                    }
                }
            }elseif($code === $etape){
                $isNext = true;
            }
        }

        return null; /** @TODO */
    }



    private function build(): void
    {
        $this->refresh();

        $sql       = "
        SELECT
          w.etape_code,
          w.structure_id,
          str.libelle_court structure_libelle,
          str.ids structure_ids,
          w.atteignable,
          w.objectif,
          w.partiel,
          w.realisation,
          w.why_non_atteignable
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
        $stmt      = $this->service->getBdd()->selectEach($sql, $sqlParams);

        $rowsByEtape   = [];
        $activeEtapes  = [];

        while ($d = $stmt->next()) {
            mpg_lower($d);

            $etapeCode = $d['etape_code'];

            if (!isset($activeEtapes[$etapeCode])) {
                $activeEtapes[$etapeCode] = $this->workflowEtapes[$etapeCode];
            }

            $rowsByEtape[$etapeCode][] = $d;
        }

        foreach ($activeEtapes as $etapeCode => $etape) {
            foreach ($rowsByEtape[$etapeCode] as $d) {
                $this->buildEtape(
                    $etape,
                    (int)$d['structure_id'],
                    $d['structure_libelle'],
                    $d['structure_ids'],
                    (bool)$d['atteignable'],
                    (float)$d['objectif'],
                    (float)$d['realisation'],
                    $d['why_non_atteignable']
                );
            }

            if ($this->getStructure() && !isset($this->fdr[$etapeCode])) {
                $this->buildEtapeVide($etape);
            }
        }

        foreach ($this->fdr as $fdre) {
            if (count($fdre->structures) == 1) {
                //$fdre->structures = []; // Pas de détail par structures s'il n'y en a qu'une
            }
        }

        $this->builted = true;
    }



    private function buildEtape(WorkflowEtape $etape, int $structureId, ?string $structureLibelle, ?string $structureIds, bool $atteignable, float $objectif, float $realisation, ?string $whyNonAtteignable): void
    {
        $inStructure = null;
        $inMain      = false;

        if (!$this->getStructure()) {
            // Périmètre établissement
            $inMain      = true;
            $inStructure = $structureId !== 0 ? $structureId : null;
        } else {
            // Périmètre composante : on garde le global, la composante et ses sous-structures.
            if ($structureId === 0 || str_contains($structureIds ?? '', '-' . $this->getStructure()->getId() . '-')) {
                $inMain = true;
            }
        }

        if ($inMain) {
            if (!array_key_exists($etape->getCode(), $this->fdr)) {
                $fdre = $this->createEtape($etape);
                $fdre->whyNonAtteignable = $this->makeWhyNonAtteignable($whyNonAtteignable);

                $this->fdr[$etape->getCode()] = $fdre;
            }else{
                $fdre = $this->fdr[$etape->getCode()];
            }

            $fdre->objectif    += $objectif;
            $fdre->realisation += $realisation;
            if (!$atteignable) {
                $fdre->atteignable = false;

                $arrayWNA = $this->makeWhyNonAtteignable($whyNonAtteignable);
                foreach ($arrayWNA as $wa) {
                    if (!in_array($wa, $fdre->whyNonAtteignable)) {
                        $fdre->whyNonAtteignable[] = $wa;
                    }
                }
            }
        }

        if ($inStructure !== null) {
            $fdre = $this->fdr[$etape->getCode()];
            $fdres                          = new FeuilleDeRouteEtape($this, $this->service, $etape);
            $fdres->numero                  = count($fdre->structures) + 1;
            $fdres->libelle                 = $structureLibelle;
            $fdres->url                     = null;
            $fdres->atteignable             = $atteignable;
            $fdres->objectif                = $objectif;
            $fdres->realisation             = $realisation;
            $fdre->structures[$structureId] = $fdres;
        }
    }



    private function buildEtapeVide(WorkflowEtape $etape): void
    {
        $fdre                     = $this->createEtape($etape);
        $fdre->atteignable        = false;
        $fdre->objectif           = 1;
        $fdre->realisation        = 0;
        $fdre->whyNonAtteignable  = [$etape->getDescNonFranchie()];

        $this->fdr[$etape->getCode()] = $fdre;
    }



    private function createEtape(WorkflowEtape $etape): FeuilleDeRouteEtape
    {
        $affectation = $this->service->getServiceContext()->getAffectation();
        $intervenant = $this->service->getServiceContext()->getIntervenant();

        $fdre          = new FeuilleDeRouteEtape($this, $this->service, $etape);
        $fdre->numero  = count($this->fdr) + 1;
        $fdre->libelle = $etape->getLibelle((bool)$intervenant);

        if ($intervenant && !$affectation) {
            $fdre->url = $this->service->getUrl($etape->getRouteIntervenant() ?: $etape->getRoute(), ['intervenant' => $this->intervenant->getId()]);
        } else {
            $fdre->url = $this->service->getUrl($etape->getRoute(), ['intervenant' => $this->intervenant->getId()]);
        }

        return $fdre;
    }



    private function makeWhyNonAtteignable(?string $whyNonAtteignable): array
    {
        if (!isset($whyNonAtteignable)) {
            return [];
        }

        $raisons = [];

        $whyNonAtteignable = json_decode($whyNonAtteignable);
        foreach ($whyNonAtteignable as $whyNonAtteignableItem) {
            if (isset($this->workflowEtapes[$whyNonAtteignableItem])) {
                $raisons[] = $this->workflowEtapes[$whyNonAtteignableItem]->getDescNonFranchie();
            } elseif (isset($whyNonAtteignableItem)) {
                $raisons[] = $whyNonAtteignableItem;
            }
        }

        return $raisons;
    }
}

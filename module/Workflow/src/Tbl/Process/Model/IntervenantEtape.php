<?php

namespace Workflow\Tbl\Process\Model;

use Intervenant\Entity\Db\TypeIntervenant;
use Workflow\Entity\Db\WorkflowEtape;

class IntervenantEtape
{
    public int    $annee;
    public int    $typeIntervenantId;
    public string $typeIntervenantCode;
    public int    $statut;
    public int    $intervenant;

    public readonly WorkflowEtape $etape;

    /** @var array|IntervenantEtapeStructure[] */
    public array $structures = [];



    public function setTypeIntervenant(TypeIntervenant $typeIntervenant): void
    {
        $this->typeIntervenantId   = $typeIntervenant->getId();
        $this->typeIntervenantCode = $typeIntervenant->getCode();
    }



    public function __construct(WorkflowEtape $etape, ?TypeIntervenant $typeIntervenant = null)
    {
        $this->etape = $etape;
        if ($typeIntervenant) {
            $this->setTypeIntervenant($typeIntervenant);
        }
    }
}
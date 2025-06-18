<?php

namespace Workflow\Tbl\Process\Model;

use Workflow\Entity\Db\WorkflowEtape;

class IntervenantEtape
{
    public int    $annee;
    public int    $typeIntervenantId;
    public string $typeIntervenantCode;
    public int    $statut;
    public int    $intervenant;

    public WorkflowEtape $etape;

    /** @var array|IntervenantEtapeStructure[] */
    public array $structures = [];
}
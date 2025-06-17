<?php

namespace Workflow\Tbl\Process\Model;

use Workflow\Entity\Db\WorkflowEtape;

class WfEtape
{
    public int $annee;
    public int $intervenant;

    public WorkflowEtape $etape;
    public int $structure;

    public int $atteignable;

    public float $objectif;
    public float $partiel;
    public float $realisation;
}
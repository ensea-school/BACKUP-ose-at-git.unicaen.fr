<?php

namespace Workflow\Tbl\Process\Model;

class IntervenantEtapeStructure
{
    public int $structure = 0;

    public bool $atteignable = true;

    public float $objectif    = 1;
    public float $partiel     = 0;
    public float $realisation = 0;
}
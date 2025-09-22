<?php

namespace Workflow\Tbl\Process\Model;

class IntervenantEtapeStructure
{
    public int $structure = 0;

    public bool $atteignable = true;

    public float $objectif    = 1;
    public float $partiel     = 0;
    public float $realisation = 0;
    public array $whyNonAtteignable = [];



    public function isFranchi(): bool
    {
        return round($this->realisation,2) >= round($this->objectif,2);
    }



    public function createfromArray(array $data): void
    {
        if (isset($data['structure'])){
            $this->structure = $data['structure'];
        }
        if (isset($data['atteignable'])){
            $this->atteignable = $data['atteignable'];
        }
        if (isset($data['objectif'])){
            $this->objectif = $data['objectif'];
        }
        if (isset($data['partiel'])){
            $this->partiel = $data['partiel'];
        }
        if (isset($data['realisation'])){
            $this->realisation = $data['realisation'];
        }
    }
}
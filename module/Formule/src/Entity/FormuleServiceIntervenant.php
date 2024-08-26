<?php

namespace Formule\Entity;

class FormuleServiceIntervenant extends FormuleIntervenant
{
    protected ?int $intervenantId = null;



    public function getIntervenantId(): ?int
    {
        return $this->intervenantId;
    }



    public function setIntervenantId(?int $intervenantId): FormuleServiceIntervenant
    {
        $this->intervenantId = $intervenantId;
        return $this;
    }

}
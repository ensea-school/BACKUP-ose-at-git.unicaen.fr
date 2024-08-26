<?php

namespace Formule\Entity;

class FormuleServiceVolumeHoraire extends FormuleVolumeHoraire
{
    protected ?int $formuleResultatIntervenantId = null;



    public function getFormuleResultatIntervenantId(): ?int
    {
        return $this->formuleResultatIntervenantId;
    }



    public function setFormuleResultatIntervenantId(?int $formuleResultatIntervenantId): FormuleServiceVolumeHoraire
    {
        $this->formuleResultatIntervenantId = $formuleResultatIntervenantId;
        return $this;
    }


}
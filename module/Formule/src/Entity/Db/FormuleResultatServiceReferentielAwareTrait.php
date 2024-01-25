<?php

namespace Formule\Entity\Db;

/**
 * Description of FormuleResultatServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielAwareTrait
{
    protected ?FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null;



    /**
     * @param FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     *
     * @return self
     */
    public function setFormuleResultatServiceReferentiel( ?FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel )
    {
        $this->formuleResultatServiceReferentiel = $formuleResultatServiceReferentiel;

        return $this;
    }



    public function getFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentiel
    {
        return $this->formuleResultatServiceReferentiel;
    }
}
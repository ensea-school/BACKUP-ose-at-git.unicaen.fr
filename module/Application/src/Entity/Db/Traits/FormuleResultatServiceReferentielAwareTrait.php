<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatServiceReferentiel;

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
    public function setFormuleResultatServiceReferentiel( FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel )
    {
        $this->formuleResultatServiceReferentiel = $formuleResultatServiceReferentiel;

        return $this;
    }



    public function getFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentiel
    {
        return $this->formuleResultatServiceReferentiel;
    }
}
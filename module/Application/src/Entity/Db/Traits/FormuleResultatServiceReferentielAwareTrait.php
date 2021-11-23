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
    /**
     * @var FormuleResultatServiceReferentiel
     */
    private $formuleResultatServiceReferentiel;





    /**
     * @param FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     * @return self
     */
    public function setFormuleResultatServiceReferentiel( FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null )
    {
        $this->formuleResultatServiceReferentiel = $formuleResultatServiceReferentiel;
        return $this;
    }



    /**
     * @return FormuleResultatServiceReferentiel
     */
    public function getFormuleResultatServiceReferentiel()
    {
        return $this->formuleResultatServiceReferentiel;
    }
}
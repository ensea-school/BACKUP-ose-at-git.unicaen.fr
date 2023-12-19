<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceReferentielService;

/**
 * Description of FormuleResultatServiceReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielServiceAwareTrait
{
    protected ?FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel = null;



    /**
     * @param FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel(?FormuleResultatServiceReferentielService $serviceFormuleResultatServiceReferentiel)
    {
        $this->serviceFormuleResultatServiceReferentiel = $serviceFormuleResultatServiceReferentiel;

        return $this;
    }



    public function getServiceFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentielService
    {
        if (empty($this->serviceFormuleResultatServiceReferentiel)) {
            $this->serviceFormuleResultatServiceReferentiel = \OseAdmin::instance()->container()->get(FormuleResultatServiceReferentielService::class);
        }

        return $this->serviceFormuleResultatServiceReferentiel;
    }
}
<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceReferentielService;

/**
 * Description of FormuleServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceReferentielServiceAwareTrait
{
    /**
     * @var FormuleServiceReferentielService
     */
    private $serviceFormuleServiceReferentiel;



    /**
     * @param FormuleServiceReferentielService $serviceFormuleServiceReferentiel
     *
     * @return self
     */
    public function setServiceFormuleServiceReferentiel(FormuleServiceReferentielService $serviceFormuleServiceReferentiel)
    {
        $this->serviceFormuleServiceReferentiel = $serviceFormuleServiceReferentiel;

        return $this;
    }



    /**
     * @return FormuleServiceReferentielService
     */
    public function getServiceFormuleServiceReferentiel()
    {
        if (empty($this->serviceFormuleServiceReferentiel)) {
            $this->serviceFormuleServiceReferentiel = \Application::$container->get(FormuleServiceReferentielService::class);
        }

        return $this->serviceFormuleServiceReferentiel;
    }
}
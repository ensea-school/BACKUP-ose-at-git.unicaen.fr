<?php

namespace Application\Service\Traits;

use Application\Service\ServiceReferentielService;

/**
 * Description of ServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielServiceAwareTrait
{
    /**
     * @var ServiceReferentielService
     */
    private $serviceServiceReferentiel;



    /**
     * @param ServiceReferentielService $serviceServiceReferentiel
     *
     * @return self
     */
    public function setServiceServiceReferentiel(ServiceReferentielService $serviceServiceReferentiel)
    {
        $this->serviceServiceReferentiel = $serviceServiceReferentiel;

        return $this;
    }



    /**
     * @return ServiceReferentielService
     */
    public function getServiceServiceReferentiel()
    {
        if (empty($this->serviceServiceReferentiel)) {
            $this->serviceServiceReferentiel = \Application::$container->get(ServiceReferentielService::class);
        }

        return $this->serviceServiceReferentiel;
    }
}
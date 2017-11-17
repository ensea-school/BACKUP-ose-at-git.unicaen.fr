<?php

namespace Application\Service\Traits;

use Application\Service\ServiceReferentiel;

/**
 * Description of ServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielAwareTrait
{
    /**
     * @var ServiceReferentiel
     */
    private $serviceServiceReferentiel;



    /**
     * @param ServiceReferentiel $serviceServiceReferentiel
     *
     * @return self
     */
    public function setServiceServiceReferentiel(ServiceReferentiel $serviceServiceReferentiel)
    {
        $this->serviceServiceReferentiel = $serviceServiceReferentiel;

        return $this;
    }



    /**
     * @return ServiceReferentiel
     */
    public function getServiceServiceReferentiel()
    {
        if (empty($this->serviceServiceReferentiel)) {
            $this->serviceServiceReferentiel = \Application::$container->get('ApplicationServiceReferentiel');
        }

        return $this->serviceServiceReferentiel;
    }
}
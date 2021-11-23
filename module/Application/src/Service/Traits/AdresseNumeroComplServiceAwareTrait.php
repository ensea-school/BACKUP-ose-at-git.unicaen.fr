<?php

namespace Application\Service\Traits;

use Application\Service\AdresseNumeroComplService;

/**
 * Description of AdresseNumeroComplServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AdresseNumeroComplServiceAwareTrait
{
    /**
     * @var AdresseNumeroComplService
     */
    private $serviceAdresseNumeroCompl;



    /**
     * @param AdresseNumeroComplService $serviceAdresseNumeroCompl
     *
     * @return self
     */
    public function setServiceAdresseNumeroCompl(AdresseNumeroComplService $serviceAdresseNumeroCompl)
    {
        $this->serviceAdresseNumeroCompl = $serviceAdresseNumeroCompl;

        return $this;
    }



    /**
     * @return AdresseNumeroComplService
     */
    public function getServiceAdresseNumeroCompl()
    {
        if (empty($this->serviceAdresseNumeroCompl)) {
            $this->serviceAdresseNumeroCompl = \Application::$container->get(AdresseNumeroComplService::class);
        }

        return $this->serviceAdresseNumeroCompl;
    }
}
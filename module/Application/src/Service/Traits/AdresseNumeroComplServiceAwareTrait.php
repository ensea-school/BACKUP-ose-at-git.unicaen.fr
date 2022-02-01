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
    protected ?AdresseNumeroComplService $serviceAdresseNumeroCompl;



    /**
     * @param AdresseNumeroComplService|null $serviceAdresseNumeroCompl
     *
     * @return self
     */
    public function setServiceAdresseNumeroCompl( ?AdresseNumeroComplService $serviceAdresseNumeroCompl )
    {
        $this->serviceAdresseNumeroCompl = $serviceAdresseNumeroCompl;

        return $this;
    }



    public function getServiceAdresseNumeroCompl(): ?AdresseNumeroComplService
    {
        if (!$this->serviceAdresseNumeroCompl){
            $this->serviceAdresseNumeroCompl = \Application::$container->get(AdresseNumeroComplService::class);
        }

        return $this->serviceAdresseNumeroCompl;
    }
}
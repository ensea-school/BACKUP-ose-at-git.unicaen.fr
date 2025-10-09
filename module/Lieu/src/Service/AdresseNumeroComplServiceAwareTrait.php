<?php

namespace Lieu\Service;

/**
 * Description of AdresseNumeroComplServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AdresseNumeroComplServiceAwareTrait
{
    protected ?AdresseNumeroComplService $serviceAdresseNumeroCompl = null;



    /**
     * @param AdresseNumeroComplService $serviceAdresseNumeroCompl
     *
     * @return self
     */
    public function setServiceAdresseNumeroCompl(?AdresseNumeroComplService $serviceAdresseNumeroCompl)
    {
        $this->serviceAdresseNumeroCompl = $serviceAdresseNumeroCompl;

        return $this;
    }



    public function getServiceAdresseNumeroCompl(): ?AdresseNumeroComplService
    {
        if (empty($this->serviceAdresseNumeroCompl)) {
            $this->serviceAdresseNumeroCompl =\Unicaen\Framework\Application\Application::getInstance()->container()->get(AdresseNumeroComplService::class);
        }

        return $this->serviceAdresseNumeroCompl;
    }
}
<?php

namespace Application\Provider\Chargens;


/**
 * Description of ChargensProviderAwareTrait
 *
 * @author UnicaenCode
 */
trait ChargensProviderAwareTrait
{
    protected ?ChargensProvider $providerChargensChargens;



    /**
     * @param ChargensProvider|null $providerChargensChargens
     *
     * @return self
     */
    public function setProviderChargensChargens( ?ChargensProvider $providerChargensChargens )
    {
        $this->providerChargensChargens = $providerChargensChargens;

        return $this;
    }



    public function getProviderChargensChargens(): ?ChargensProvider
    {
        if (!$this->providerChargensChargens){
            $this->providerChargensChargens = \Application::$container->get(ChargensProvider::class);
        }

        return $this->providerChargensChargens;
    }
}
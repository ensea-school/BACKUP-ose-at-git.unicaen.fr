<?php

namespace Application\Provider\Chargens;


/**
 * Description of ChargensProviderAwareTrait
 *
 * @author UnicaenCode
 */
trait ChargensProviderAwareTrait
{
    protected ?ChargensProvider $providerChargensChargens = null;



    /**
     * @param ChargensProvider $providerChargensChargens
     *
     * @return self
     */
    public function setProviderChargensChargens(?ChargensProvider $providerChargensChargens)
    {
        $this->providerChargensChargens = $providerChargensChargens;

        return $this;
    }



    public function getProviderChargensChargens(): ?ChargensProvider
    {
        if (empty($this->providerChargensChargens)) {
            $this->providerChargensChargens = \OseAdmin::instance()->container()->get(ChargensProvider::class);
        }

        return $this->providerChargensChargens;
    }
}
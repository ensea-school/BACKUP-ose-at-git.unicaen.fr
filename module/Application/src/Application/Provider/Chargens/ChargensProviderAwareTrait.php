<?php

namespace Application\Provider\Chargens;

trait ChargensProviderAwareTrait {

    /**
     * @var ChargensProvider
     */
    private $providerChargens;

    /**
     * @param ChargensProvider $providerChargens
     * @return self
     */
    public function setProviderChargens( ChargensProvider $providerChargens )
    {
        $this->providerChargens = $providerChargens;
        return $this;
    }



    /**
     * @return ChargensProvider
     */
    public function getProviderChargens()
    {
        if (!$this->providerChargens){
            $this->providerChargens = \Application::$container->get(ChargensProvider::class);
        }
        return $this->providerChargens;
    }

}
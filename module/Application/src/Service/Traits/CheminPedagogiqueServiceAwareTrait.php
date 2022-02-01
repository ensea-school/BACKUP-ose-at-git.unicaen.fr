<?php

namespace Application\Service\Traits;

use Application\Service\CheminPedagogiqueService;

/**
 * Description of CheminPedagogiqueServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CheminPedagogiqueServiceAwareTrait
{
    protected ?CheminPedagogiqueService $serviceCheminPedagogique = null;



    /**
     * @param CheminPedagogiqueService $serviceCheminPedagogique
     *
     * @return self
     */
    public function setServiceCheminPedagogique( ?CheminPedagogiqueService $serviceCheminPedagogique )
    {
        $this->serviceCheminPedagogique = $serviceCheminPedagogique;

        return $this;
    }



    public function getServiceCheminPedagogique(): ?CheminPedagogiqueService
    {
        if (empty($this->serviceCheminPedagogique)){
            $this->serviceCheminPedagogique = \Application::$container->get(CheminPedagogiqueService::class);
        }

        return $this->serviceCheminPedagogique;
    }
}
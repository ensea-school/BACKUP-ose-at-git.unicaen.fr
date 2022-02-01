<?php

namespace Application\Service\Traits;

use Application\Service\ElementPedagogiqueService;

/**
 * Description of ElementPedagogiqueServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueServiceAwareTrait
{
    protected ?ElementPedagogiqueService $serviceElementPedagogique;



    /**
     * @param ElementPedagogiqueService|null $serviceElementPedagogique
     *
     * @return self
     */
    public function setServiceElementPedagogique( ?ElementPedagogiqueService $serviceElementPedagogique )
    {
        $this->serviceElementPedagogique = $serviceElementPedagogique;

        return $this;
    }



    public function getServiceElementPedagogique(): ?ElementPedagogiqueService
    {
        if (!$this->serviceElementPedagogique){
            $this->serviceElementPedagogique = \Application::$container->get(ElementPedagogiqueService::class);
        }

        return $this->serviceElementPedagogique;
    }
}
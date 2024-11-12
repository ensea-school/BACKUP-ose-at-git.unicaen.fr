<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\ElementPedagogiqueService;

/**
 * Description of ElementPedagogiqueServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueServiceAwareTrait
{
    protected ?ElementPedagogiqueService $serviceElementPedagogique = null;



    /**
     * @param ElementPedagogiqueService $serviceElementPedagogique
     *
     * @return self
     */
    public function setServiceElementPedagogique(?ElementPedagogiqueService $serviceElementPedagogique)
    {
        $this->serviceElementPedagogique = $serviceElementPedagogique;

        return $this;
    }



    public function getServiceElementPedagogique(): ?ElementPedagogiqueService
    {
        if (empty($this->serviceElementPedagogique)) {
            $this->serviceElementPedagogique = \AppAdmin::container()->get(ElementPedagogiqueService::class);
        }

        return $this->serviceElementPedagogique;
    }
}
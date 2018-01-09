<?php

namespace Application\Service\Traits;

use Application\Service\ElementPedagogiqueService;

/**
 * Description of ElementPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueServiceAwareTrait
{
    /**
     * @var ElementPedagogiqueService
     */
    private $serviceElementPedagogique;



    /**
     * @param ElementPedagogiqueService $serviceElementPedagogique
     *
     * @return self
     */
    public function setServiceElementPedagogique(ElementPedagogiqueService $serviceElementPedagogique)
    {
        $this->serviceElementPedagogique = $serviceElementPedagogique;

        return $this;
    }



    /**
     * @return ElementPedagogiqueService
     */
    public function getServiceElementPedagogique()
    {
        if (empty($this->serviceElementPedagogique)) {
            $this->serviceElementPedagogique = \Application::$container->get(ElementPedagogiqueService::class);
        }

        return $this->serviceElementPedagogique;
    }
}
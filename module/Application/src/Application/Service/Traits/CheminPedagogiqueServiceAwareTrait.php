<?php

namespace Application\Service\Traits;

use Application\Service\CheminPedagogiqueService;

/**
 * Description of CheminPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait CheminPedagogiqueServiceAwareTrait
{
    /**
     * @var CheminPedagogiqueService
     */
    private $serviceCheminPedagogique;



    /**
     * @param CheminPedagogiqueService $serviceCheminPedagogique
     *
     * @return self
     */
    public function setServiceCheminPedagogique(CheminPedagogiqueService $serviceCheminPedagogique)
    {
        $this->serviceCheminPedagogique = $serviceCheminPedagogique;

        return $this;
    }



    /**
     * @return CheminPedagogiqueService
     */
    public function getServiceCheminPedagogique()
    {
        if (empty($this->serviceCheminPedagogique)) {
            $this->serviceCheminPedagogique = \Application::$container->get(CheminPedagogiqueService::class);
        }

        return $this->serviceCheminPedagogique;
    }
}
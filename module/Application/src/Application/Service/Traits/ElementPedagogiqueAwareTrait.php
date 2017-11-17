<?php

namespace Application\Service\Traits;

use Application\Service\ElementPedagogique;

/**
 * Description of ElementPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueAwareTrait
{
    /**
     * @var ElementPedagogique
     */
    private $serviceElementPedagogique;



    /**
     * @param ElementPedagogique $serviceElementPedagogique
     *
     * @return self
     */
    public function setServiceElementPedagogique(ElementPedagogique $serviceElementPedagogique)
    {
        $this->serviceElementPedagogique = $serviceElementPedagogique;

        return $this;
    }



    /**
     * @return ElementPedagogique
     */
    public function getServiceElementPedagogique()
    {
        if (empty($this->serviceElementPedagogique)) {
            $this->serviceElementPedagogique = \Application::$container->get('ApplicationElementPedagogique');
        }

        return $this->serviceElementPedagogique;
    }
}
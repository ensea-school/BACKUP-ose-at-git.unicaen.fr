<?php

namespace Application\Service\Interfaces;

use Application\Service\ElementPedagogique;
use RuntimeException;

/**
 * Description of ElementPedagogiqueAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementPedagogiqueAwareInterface
{
    /**
     * @param ElementPedagogique $serviceElementPedagogique
     * @return self
     */
    public function setServiceElementPedagogique( ElementPedagogique $serviceElementPedagogique );



    /**
     * @return ElementPedagogiqueAwareInterface
     * @throws RuntimeException
     */
    public function getServiceElementPedagogique();
}
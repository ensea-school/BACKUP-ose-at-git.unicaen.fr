<?php

namespace Application\Service\Interfaces;

use Application\Service\CheminPedagogique;
use RuntimeException;

/**
 * Description of CheminPedagogiqueAwareInterface
 *
 * @author UnicaenCode
 */
interface CheminPedagogiqueAwareInterface
{
    /**
     * @param CheminPedagogique $serviceCheminPedagogique
     * @return self
     */
    public function setServiceCheminPedagogique( CheminPedagogique $serviceCheminPedagogique );



    /**
     * @return CheminPedagogiqueAwareInterface
     * @throws RuntimeException
     */
    public function getServiceCheminPedagogique();
}
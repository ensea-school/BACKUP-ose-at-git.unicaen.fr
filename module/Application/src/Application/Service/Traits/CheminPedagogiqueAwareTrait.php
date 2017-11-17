<?php

namespace Application\Service\Traits;

use Application\Service\CheminPedagogique;

/**
 * Description of CheminPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait CheminPedagogiqueAwareTrait
{
    /**
     * @var CheminPedagogique
     */
    private $serviceCheminPedagogique;



    /**
     * @param CheminPedagogique $serviceCheminPedagogique
     *
     * @return self
     */
    public function setServiceCheminPedagogique(CheminPedagogique $serviceCheminPedagogique)
    {
        $this->serviceCheminPedagogique = $serviceCheminPedagogique;

        return $this;
    }



    /**
     * @return CheminPedagogique
     */
    public function getServiceCheminPedagogique()
    {
        if (empty($this->serviceCheminPedagogique)) {
            $this->serviceCheminPedagogique = \Application::$container->get('ApplicationCheminPedagogique');
        }

        return $this->serviceCheminPedagogique;
    }
}
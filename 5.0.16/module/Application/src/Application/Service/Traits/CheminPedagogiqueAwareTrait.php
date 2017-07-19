<?php

namespace Application\Service\Traits;

use Application\Service\CheminPedagogique;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceCheminPedagogique( CheminPedagogique $serviceCheminPedagogique )
    {
        $this->serviceCheminPedagogique = $serviceCheminPedagogique;
        return $this;
    }



    /**
     * @return CheminPedagogique
     * @throws RuntimeException
     */
    public function getServiceCheminPedagogique()
    {
        if (empty($this->serviceCheminPedagogique)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceCheminPedagogique = $serviceLocator->get('ApplicationCheminPedagogique');
        }
        return $this->serviceCheminPedagogique;
    }
}
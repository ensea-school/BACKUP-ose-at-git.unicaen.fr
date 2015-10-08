<?php

namespace Application\Service\Traits;

use Application\Service\ElementPedagogique;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceElementPedagogique( ElementPedagogique $serviceElementPedagogique )
    {
        $this->serviceElementPedagogique = $serviceElementPedagogique;
        return $this;
    }



    /**
     * @return ElementPedagogique
     * @throws RuntimeException
     */
    public function getServiceElementPedagogique()
    {
        if (empty($this->serviceElementPedagogique)){
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
        $this->serviceElementPedagogique = $serviceLocator->get('ApplicationElementPedagogique');
        }
        return $this->serviceElementPedagogique;
    }
}
<?php

namespace Application\Service\Traits;

use Application\Service\DotationService;
use Application\Module;
use RuntimeException;

/**
 * Description of DotationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationServiceAwareTrait
{
    /**
     * @var DotationService
     */
    private $serviceDotation;





    /**
     * @param DotationService $serviceDotation
     * @return self
     */
    public function setServiceDotation( DotationService $serviceDotation )
    {
        $this->serviceDotation = $serviceDotation;
        return $this;
    }



    /**
     * @return DotationService
     * @throws RuntimeException
     */
    public function getServiceDotation()
    {
        if (empty($this->serviceDotation)){
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
        $this->serviceDotation = $serviceLocator->get('applicationDotation');
        }
        return $this->serviceDotation;
    }
}
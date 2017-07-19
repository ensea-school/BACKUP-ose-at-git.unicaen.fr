<?php

namespace Application\Service\Traits;

use Application\Service\TypeDotationService;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeDotationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeDotationServiceAwareTrait
{
    /**
     * @var TypeDotationService
     */
    private $serviceTypeDotation;





    /**
     * @param TypeDotationService $serviceTypeDotation
     * @return self
     */
    public function setServiceTypeDotation( TypeDotationService $serviceTypeDotation )
    {
        $this->serviceTypeDotation = $serviceTypeDotation;
        return $this;
    }



    /**
     * @return TypeDotationService
     * @throws RuntimeException
     */
    public function getServiceTypeDotation()
    {
        if (empty($this->serviceTypeDotation)){
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
            $this->serviceTypeDotation = $serviceLocator->get('ApplicationTypeDotation');
        }
        return $this->serviceTypeDotation;
    }
}
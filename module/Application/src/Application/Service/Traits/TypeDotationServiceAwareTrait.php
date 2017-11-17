<?php

namespace Application\Service\Traits;

use Application\Service\TypeDotationService;

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
     *
     * @return self
     */
    public function setServiceTypeDotation(TypeDotationService $serviceTypeDotation)
    {
        $this->serviceTypeDotation = $serviceTypeDotation;

        return $this;
    }



    /**
     * @return TypeDotationService
     */
    public function getServiceTypeDotation()
    {
        if (empty($this->serviceTypeDotation)) {
            $this->serviceTypeDotation = \Application::$container->get('ApplicationTypeDotation');
        }

        return $this->serviceTypeDotation;
    }
}
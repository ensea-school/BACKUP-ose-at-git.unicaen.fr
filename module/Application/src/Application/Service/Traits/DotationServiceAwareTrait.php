<?php

namespace Application\Service\Traits;

use Application\Service\DotationService;

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
     *
     * @return self
     */
    public function setServiceDotation(DotationService $serviceDotation)
    {
        $this->serviceDotation = $serviceDotation;

        return $this;
    }



    /**
     * @return DotationService
     */
    public function getServiceDotation()
    {
        if (empty($this->serviceDotation)) {
            $this->serviceDotation = \Application::$container->get('applicationDotation');
        }

        return $this->serviceDotation;
    }
}
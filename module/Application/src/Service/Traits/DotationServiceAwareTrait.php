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
    protected ?DotationService $serviceDotation;



    /**
     * @param DotationService|null $serviceDotation
     *
     * @return self
     */
    public function setServiceDotation( ?DotationService $serviceDotation )
    {
        $this->serviceDotation = $serviceDotation;

        return $this;
    }



    public function getServiceDotation(): ?DotationService
    {
        if (!$this->serviceDotation){
            $this->serviceDotation = \Application::$container->get(DotationService::class);
        }

        return $this->serviceDotation;
    }
}
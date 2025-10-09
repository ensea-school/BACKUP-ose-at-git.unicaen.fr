<?php

namespace Paiement\Service;


/**
 * Description of DotationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationServiceAwareTrait
{
    protected ?DotationService $serviceDotation = null;



    /**
     * @param DotationService $serviceDotation
     *
     * @return self
     */
    public function setServiceDotation(?DotationService $serviceDotation)
    {
        $this->serviceDotation = $serviceDotation;

        return $this;
    }



    public function getServiceDotation(): ?DotationService
    {
        if (empty($this->serviceDotation)) {
            $this->serviceDotation = \Unicaen\Framework\Application\Application::getInstance()->container()->get(DotationService::class);
        }

        return $this->serviceDotation;
    }
}
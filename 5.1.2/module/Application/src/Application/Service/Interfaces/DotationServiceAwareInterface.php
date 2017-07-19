<?php

namespace Application\Service\Interfaces;

use Application\Service\DotationService;
use RuntimeException;

/**
 * Description of DotationServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface DotationServiceAwareInterface
{
    /**
     * @param DotationService $serviceDotation
     * @return self
     */
    public function setServiceDotation( DotationService $serviceDotation );



    /**
     * @return DotationServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceDotation();
}
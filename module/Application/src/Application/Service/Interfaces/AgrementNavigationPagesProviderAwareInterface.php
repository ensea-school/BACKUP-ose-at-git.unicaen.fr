<?php

namespace Application\Service\Interfaces;

use Application\Service\AgrementNavigationPagesProvider;
use RuntimeException;

/**
 * Description of AgrementNavigationPagesProviderAwareInterface
 *
 * @author UnicaenCode
 */
interface AgrementNavigationPagesProviderAwareInterface
{
    /**
     * @param AgrementNavigationPagesProvider $serviceAgrementNavigationPagesProvider
     * @return self
     */
    public function setServiceAgrementNavigationPagesProvider( AgrementNavigationPagesProvider $serviceAgrementNavigationPagesProvider );



    /**
     * @return AgrementNavigationPagesProviderAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAgrementNavigationPagesProvider();
}
<?php

namespace Application\Service\Interfaces;

use Application\Service\AgrementIntervenantNavigationPagesProvider;
use RuntimeException;

/**
 * Description of AgrementIntervenantNavigationPagesProviderAwareInterface
 *
 * @author UnicaenCode
 */
interface AgrementIntervenantNavigationPagesProviderAwareInterface
{
    /**
     * @param AgrementIntervenantNavigationPagesProvider $serviceAgrementIntervenantNavigationPagesProvider
     * @return self
     */
    public function setServiceAgrementIntervenantNavigationPagesProvider( AgrementIntervenantNavigationPagesProvider $serviceAgrementIntervenantNavigationPagesProvider );



    /**
     * @return AgrementIntervenantNavigationPagesProviderAwareInterface
     * @throws RuntimeException
     */
    public function getServiceAgrementIntervenantNavigationPagesProvider();
}
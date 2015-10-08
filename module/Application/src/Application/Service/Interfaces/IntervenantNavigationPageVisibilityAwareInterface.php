<?php

namespace Application\Service\Interfaces;

use Application\Service\IntervenantNavigationPageVisibility;
use RuntimeException;

/**
 * Description of IntervenantNavigationPageVisibilityAwareInterface
 *
 * @author UnicaenCode
 */
interface IntervenantNavigationPageVisibilityAwareInterface
{
    /**
     * @param IntervenantNavigationPageVisibility $serviceIntervenantNavigationPageVisibility
     * @return self
     */
    public function setServiceIntervenantNavigationPageVisibility( IntervenantNavigationPageVisibility $serviceIntervenantNavigationPageVisibility );



    /**
     * @return IntervenantNavigationPageVisibilityAwareInterface
     * @throws RuntimeException
     */
    public function getServiceIntervenantNavigationPageVisibility();
}
<?php

namespace Application\Service\Traits;

use Application\Service\IntervenantNavigationPageVisibility;
use Application\Module;
use RuntimeException;

/**
 * Description of IntervenantNavigationPageVisibilityAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantNavigationPageVisibilityAwareTrait
{
    /**
     * @var IntervenantNavigationPageVisibility
     */
    private $serviceIntervenantNavigationPageVisibility;





    /**
     * @param IntervenantNavigationPageVisibility $serviceIntervenantNavigationPageVisibility
     * @return self
     */
    public function setServiceIntervenantNavigationPageVisibility( IntervenantNavigationPageVisibility $serviceIntervenantNavigationPageVisibility )
    {
        $this->serviceIntervenantNavigationPageVisibility = $serviceIntervenantNavigationPageVisibility;
        return $this;
    }



    /**
     * @return IntervenantNavigationPageVisibility
     * @throws RuntimeException
     */
    public function getServiceIntervenantNavigationPageVisibility()
    {
        if (empty($this->serviceIntervenantNavigationPageVisibility)){
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
        $this->serviceIntervenantNavigationPageVisibility = $serviceLocator->get('IntervenantNavigationPageVisibility');
        }
        return $this->serviceIntervenantNavigationPageVisibility;
    }
}
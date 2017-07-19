<?php

namespace Application\Service\Traits;

use Application\Service\ServiceReferentiel;
use Application\Module;
use RuntimeException;

/**
 * Description of ServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielAwareTrait
{
    /**
     * @var ServiceReferentiel
     */
    private $serviceServiceReferentiel;





    /**
     * @param ServiceReferentiel $serviceServiceReferentiel
     * @return self
     */
    public function setServiceServiceReferentiel( ServiceReferentiel $serviceServiceReferentiel )
    {
        $this->serviceServiceReferentiel = $serviceServiceReferentiel;
        return $this;
    }



    /**
     * @return ServiceReferentiel
     * @throws RuntimeException
     */
    public function getServiceServiceReferentiel()
    {
        if (empty($this->serviceServiceReferentiel)){
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
        $this->serviceServiceReferentiel = $serviceLocator->get('ApplicationServiceReferentiel');
        }
        return $this->serviceServiceReferentiel;
    }
}
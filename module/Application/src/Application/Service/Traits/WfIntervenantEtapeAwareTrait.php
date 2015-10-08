<?php

namespace Application\Service\Traits;

use Application\Service\WfIntervenantEtape;
use Application\Module;
use RuntimeException;

/**
 * Description of WfIntervenantEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait WfIntervenantEtapeAwareTrait
{
    /**
     * @var WfIntervenantEtape
     */
    private $serviceWfIntervenantEtape;





    /**
     * @param WfIntervenantEtape $serviceWfIntervenantEtape
     * @return self
     */
    public function setServiceWfIntervenantEtape( WfIntervenantEtape $serviceWfIntervenantEtape )
    {
        $this->serviceWfIntervenantEtape = $serviceWfIntervenantEtape;
        return $this;
    }



    /**
     * @return WfIntervenantEtape
     * @throws RuntimeException
     */
    public function getServiceWfIntervenantEtape()
    {
        if (empty($this->serviceWfIntervenantEtape)){
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
        $this->serviceWfIntervenantEtape = $serviceLocator->get('WfIntervenantEtapeService');
        }
        return $this->serviceWfIntervenantEtape;
    }
}
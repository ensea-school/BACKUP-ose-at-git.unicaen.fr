<?php

namespace Application\Service\Traits;

use Application\Service\NiveauEtape;
use Application\Module;
use RuntimeException;

/**
 * Description of NiveauEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauEtapeAwareTrait
{
    /**
     * @var NiveauEtape
     */
    private $serviceNiveauEtape;





    /**
     * @param NiveauEtape $serviceNiveauEtape
     * @return self
     */
    public function setServiceNiveauEtape( NiveauEtape $serviceNiveauEtape )
    {
        $this->serviceNiveauEtape = $serviceNiveauEtape;
        return $this;
    }



    /**
     * @return NiveauEtape
     * @throws RuntimeException
     */
    public function getServiceNiveauEtape()
    {
        if (empty($this->serviceNiveauEtape)){
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
        $this->serviceNiveauEtape = $serviceLocator->get('ApplicationNiveauEtape');
        }
        return $this->serviceNiveauEtape;
    }
}
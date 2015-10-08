<?php

namespace Application\Service\Traits;

use Application\Service\Indicateur;
use Application\Module;
use RuntimeException;

/**
 * Description of IndicateurAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurAwareTrait
{
    /**
     * @var Indicateur
     */
    private $serviceIndicateur;





    /**
     * @param Indicateur $serviceIndicateur
     * @return self
     */
    public function setServiceIndicateur( Indicateur $serviceIndicateur )
    {
        $this->serviceIndicateur = $serviceIndicateur;
        return $this;
    }



    /**
     * @return Indicateur
     * @throws RuntimeException
     */
    public function getServiceIndicateur()
    {
        if (empty($this->serviceIndicateur)){
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
        $this->serviceIndicateur = $serviceLocator->get('applicationIndicateur');
        }
        return $this->serviceIndicateur;
    }
}
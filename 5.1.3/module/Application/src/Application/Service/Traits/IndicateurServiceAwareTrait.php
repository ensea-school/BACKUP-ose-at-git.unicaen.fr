<?php

namespace Application\Service\Traits;

use Application\Service\IndicateurService;
use RuntimeException;

/**
 * Description of IndicateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurServiceAwareTrait
{
    /**
     * @var IndicateurService
     */
    private $serviceIndicateur;





    /**
     * @param IndicateurService $serviceIndicateur
     * @return self
     */
    public function setServiceIndicateur( IndicateurService $serviceIndicateur )
    {
        $this->serviceIndicateur = $serviceIndicateur;
        return $this;
    }



    /**
     * @return IndicateurService
     * @throws RuntimeException
     */
    public function getServiceIndicateur()
    {
        if (empty($this->serviceIndicateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
            $this->serviceIndicateur = $serviceLocator->get('applicationIndicateur');
        }
        return $this->serviceIndicateur;
    }
}
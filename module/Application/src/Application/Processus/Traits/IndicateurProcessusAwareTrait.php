<?php

namespace Application\Processus\Traits;

use Application\Processus\IndicateurProcessus;
use Application\Module;
use RuntimeException;

/**
 * Description of IndicateurProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurProcessusAwareTrait
{
    /**
     * @var IndicateurProcessus
     */
    private $processusIndicateur;





    /**
     * @param IndicateurProcessus $processusIndicateur
     * @return self
     */
    public function setProcessusIndicateur( IndicateurProcessus $processusIndicateur )
    {
        $this->processusIndicateur = $processusIndicateur;
        return $this;
    }



    /**
     * @return IndicateurProcessus
     * @throws RuntimeException
     */
    public function getProcessusIndicateur()
    {
        if (empty($this->processusIndicateur)){
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
            $this->processusIndicateur = $serviceLocator->get('processusIndicateur');
        }
        return $this->processusIndicateur;
    }
}
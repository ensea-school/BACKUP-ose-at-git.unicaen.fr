<?php

namespace Application\Service\Traits;

use Application\Service\Dossier;
use Application\Module;
use RuntimeException;

/**
 * Description of DossierAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAwareTrait
{
    /**
     * @var Dossier
     */
    private $serviceDossier;





    /**
     * @param Dossier $serviceDossier
     * @return self
     */
    public function setServiceDossier( Dossier $serviceDossier )
    {
        $this->serviceDossier = $serviceDossier;
        return $this;
    }



    /**
     * @return Dossier
     * @throws RuntimeException
     */
    public function getServiceDossier()
    {
        if (empty($this->serviceDossier)){
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
        $this->serviceDossier = $serviceLocator->get('ApplicationDossier');
        }
        return $this->serviceDossier;
    }
}
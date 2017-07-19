<?php

namespace Application\Service\Traits;

use Application\Service\FichierService;
use Application\Module;
use RuntimeException;

/**
 * Description of FichierServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FichierServiceAwareTrait
{
    /**
     * @var FichierService
     */
    private $serviceFichier;





    /**
     * @param FichierService $serviceFichier
     * @return self
     */
    public function setServiceFichier( FichierService $serviceFichier )
    {
        $this->serviceFichier = $serviceFichier;
        return $this;
    }



    /**
     * @return FichierService
     * @throws RuntimeException
     */
    public function getServiceFichier()
    {
        if (empty($this->serviceFichier)){
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
            $this->serviceFichier = $serviceLocator->get('applicationFichier');
        }
        return $this->serviceFichier;
    }
}
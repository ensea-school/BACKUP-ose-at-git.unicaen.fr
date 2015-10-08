<?php

namespace Application\Service\Traits;

use Application\Service\Contrat;
use Application\Module;
use RuntimeException;

/**
 * Description of ContratAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratAwareTrait
{
    /**
     * @var Contrat
     */
    private $serviceContrat;





    /**
     * @param Contrat $serviceContrat
     * @return self
     */
    public function setServiceContrat( Contrat $serviceContrat )
    {
        $this->serviceContrat = $serviceContrat;
        return $this;
    }



    /**
     * @return Contrat
     * @throws RuntimeException
     */
    public function getServiceContrat()
    {
        if (empty($this->serviceContrat)){
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
        $this->serviceContrat = $serviceLocator->get('ApplicationContrat');
        }
        return $this->serviceContrat;
    }
}
<?php

namespace Application\Processus\Traits;

use Application\Processus\ContratProcessus;
use Application\Module;
use RuntimeException;

/**
 * Description of ContratProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratProcessusAwareTrait
{
    /**
     * @var ContratProcessus
     */
    private $processusContrat;





    /**
     * @param ContratProcessus $processusContrat
     * @return self
     */
    public function setProcessusContrat( ContratProcessus $processusContrat )
    {
        $this->processusContrat = $processusContrat;
        return $this;
    }



    /**
     * @return ContratProcessus
     * @throws RuntimeException
     */
    public function getProcessusContrat()
    {
        if (empty($this->processusContrat)){
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
            $this->processusContrat = $serviceLocator->get('processusContrat');
        }
        return $this->processusContrat;
    }
}
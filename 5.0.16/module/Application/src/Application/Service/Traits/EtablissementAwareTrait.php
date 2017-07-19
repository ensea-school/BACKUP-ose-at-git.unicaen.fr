<?php

namespace Application\Service\Traits;

use Application\Service\Etablissement;
use Application\Module;
use RuntimeException;

/**
 * Description of EtablissementAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementAwareTrait
{
    /**
     * @var Etablissement
     */
    private $serviceEtablissement;





    /**
     * @param Etablissement $serviceEtablissement
     * @return self
     */
    public function setServiceEtablissement( Etablissement $serviceEtablissement )
    {
        $this->serviceEtablissement = $serviceEtablissement;
        return $this;
    }



    /**
     * @return Etablissement
     * @throws RuntimeException
     */
    public function getServiceEtablissement()
    {
        if (empty($this->serviceEtablissement)){
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
        $this->serviceEtablissement = $serviceLocator->get('ApplicationEtablissement');
        }
        return $this->serviceEtablissement;
    }
}
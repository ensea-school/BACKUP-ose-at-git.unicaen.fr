<?php

namespace Application\Service\Traits;

use Application\Service\Annee;
use Application\Module;
use RuntimeException;

/**
 * Description of AnneeAwareTrait
 *
 * @author UnicaenCode
 */
trait AnneeAwareTrait
{
    /**
     * @var Annee
     */
    private $serviceAnnee;





    /**
     * @param Annee $serviceAnnee
     * @return self
     */
    public function setServiceAnnee( Annee $serviceAnnee )
    {
        $this->serviceAnnee = $serviceAnnee;
        return $this;
    }



    /**
     * @return Annee
     * @throws RuntimeException
     */
    public function getServiceAnnee()
    {
        if (empty($this->serviceAnnee)){
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
        $this->serviceAnnee = $serviceLocator->get('ApplicationAnnee');
        }
        return $this->serviceAnnee;
    }
}
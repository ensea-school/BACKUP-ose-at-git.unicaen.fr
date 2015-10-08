<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiement;
use Application\Module;
use RuntimeException;

/**
 * Description of MiseEnPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementAwareTrait
{
    /**
     * @var MiseEnPaiement
     */
    private $serviceMiseEnPaiement;





    /**
     * @param MiseEnPaiement $serviceMiseEnPaiement
     * @return self
     */
    public function setServiceMiseEnPaiement( MiseEnPaiement $serviceMiseEnPaiement )
    {
        $this->serviceMiseEnPaiement = $serviceMiseEnPaiement;
        return $this;
    }



    /**
     * @return MiseEnPaiement
     * @throws RuntimeException
     */
    public function getServiceMiseEnPaiement()
    {
        if (empty($this->serviceMiseEnPaiement)){
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
        $this->serviceMiseEnPaiement = $serviceLocator->get('ApplicationMiseEnPaiement');
        }
        return $this->serviceMiseEnPaiement;
    }
}
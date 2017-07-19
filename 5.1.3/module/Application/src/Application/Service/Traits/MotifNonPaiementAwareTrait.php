<?php

namespace Application\Service\Traits;

use Application\Service\MotifNonPaiement;
use Application\Module;
use RuntimeException;

/**
 * Description of MotifNonPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementAwareTrait
{
    /**
     * @var MotifNonPaiement
     */
    private $serviceMotifNonPaiement;





    /**
     * @param MotifNonPaiement $serviceMotifNonPaiement
     * @return self
     */
    public function setServiceMotifNonPaiement( MotifNonPaiement $serviceMotifNonPaiement )
    {
        $this->serviceMotifNonPaiement = $serviceMotifNonPaiement;
        return $this;
    }



    /**
     * @return MotifNonPaiement
     * @throws RuntimeException
     */
    public function getServiceMotifNonPaiement()
    {
        if (empty($this->serviceMotifNonPaiement)){
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
        $this->serviceMotifNonPaiement = $serviceLocator->get('ApplicationMotifNonPaiement');
        }
        return $this->serviceMotifNonPaiement;
    }
}
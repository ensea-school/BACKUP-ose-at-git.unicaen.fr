<?php

namespace Application\Service\Traits;

use Application\Service\MotifNonPaiement;
use Common\Exception\RuntimeException;

trait MotifNonPaiementAwareTrait
{
    /**
     * description
     *
     * @var MotifNonPaiement
     */
    private $serviceMotifNonPaiement;

    /**
     *
     * @param MotifNonPaiement $serviceMotifNonPaiement
     * @return self
     */
    public function setServiceMotifNonPaiement( MotifNonPaiement $serviceMotifNonPaiement )
    {
        $this->serviceMotifNonPaiement = $serviceMotifNonPaiement;
        return $this;
    }

    /**
     *
     * @return MotifNonPaiement
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceMotifNonPaiement()
    {
        if (empty($this->serviceMotifNonPaiement)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationMotifNonPaiement');
        }else{
            return $this->serviceMotifNonPaiement;
        }
    }

}
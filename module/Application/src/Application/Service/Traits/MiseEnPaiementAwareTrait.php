<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiement;
use Common\Exception\RuntimeException;

trait MiseEnPaiementAwareTrait
{
    /**
     * description
     *
     * @var MiseEnPaiement
     */
    private $serviceMiseEnPaiement;

    /**
     *
     * @param MiseEnPaiement $serviceMiseEnPaiement
     * @return self
     */
    public function setServiceMiseEnPaiement( MiseEnPaiement $serviceMiseEnPaiement )
    {
        $this->serviceMiseEnPaiement = $serviceMiseEnPaiement;
        return $this;
    }

    /**
     *
     * @return MiseEnPaiement
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceMiseEnPaiement()
    {
        if (empty($this->serviceMiseEnPaiement)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationMiseEnPaiement');
        }else{
            return $this->serviceMiseEnPaiement;
        }
    }

}
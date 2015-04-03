<?php

namespace Application\Service\Traits;

use Application\Service\Contrat;
use Common\Exception\RuntimeException;

trait ContratAwareTrait
{
    /**
     * description
     *
     * @var Contrat
     */
    private $serviceContrat;

    /**
     *
     * @param Contrat $serviceContrat
     * @return self
     */
    public function setServiceContrat( Contrat $serviceContrat )
    {
        $this->serviceContrat = $serviceContrat;
        return $this;
    }

    /**
     *
     * @return Contrat
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceContrat()
    {
        if (empty($this->serviceContrat)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationContrat');
        }else{
            return $this->serviceContrat;
        }
    }

}
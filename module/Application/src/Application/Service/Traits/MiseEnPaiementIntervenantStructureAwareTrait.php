<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementIntervenantStructure;
use Common\Exception\RuntimeException;

trait MiseEnPaiementIntervenantStructureAwareTrait
{
    /**
     * description
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    private $serviceMiseEnPaiementIntervenantStructure;

    /**
     *
     * @param MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure
     * @return self
     */
    public function setServiceMiseEnPaiementIntervenantStructure( MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure )
    {
        $this->serviceMiseEnPaiementIntervenantStructure = $serviceMiseEnPaiementIntervenantStructure;
        return $this;
    }

    /**
     *
     * @return MiseEnPaiementIntervenantStructure
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceMiseEnPaiementIntervenantStructure()
    {
        if (empty($this->serviceMiseEnPaiementIntervenantStructure)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationMiseEnPaiementIntervenantStructure');
        }else{
            return $this->serviceMiseEnPaiementIntervenantStructure;
        }
    }

}
<?php

namespace Application\Service\Traits;

use Application\Service\Dossier;
use Common\Exception\RuntimeException;

trait DossierAwareTrait
{
    /**
     * description
     *
     * @var Dossier
     */
    private $serviceDossier;

    /**
     *
     * @param Dossier $serviceDossier
     * @return self
     */
    public function setServiceDossier( Dossier $serviceDossier )
    {
        $this->serviceDossier = $serviceDossier;
        return $this;
    }

    /**
     *
     * @return Dossier
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceDossier()
    {
        if (empty($this->serviceDossier)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationDossier');
        }else{
            return $this->serviceDossier;
        }
    }

}
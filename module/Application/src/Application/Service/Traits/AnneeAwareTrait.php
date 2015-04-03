<?php

namespace Application\Service\Traits;

use Application\Service\Annee;
use Common\Exception\RuntimeException;

trait AnneeAwareTrait
{
    /**
     * description
     *
     * @var Annee
     */
    private $serviceAnnee;

    /**
     *
     * @param Annee $serviceAnnee
     * @return self
     */
    public function setServiceAnnee( Annee $serviceAnnee )
    {
        $this->serviceAnnee = $serviceAnnee;
        return $this;
    }

    /**
     *
     * @return Annee
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceAnnee()
    {
        if (empty($this->serviceAnnee)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationAnnee');
        }else{
            return $this->serviceAnnee;
        }
    }

}
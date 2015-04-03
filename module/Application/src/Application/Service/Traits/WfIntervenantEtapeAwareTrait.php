<?php

namespace Application\Service\Traits;

use Application\Service\WfIntervenantEtape;
use Common\Exception\RuntimeException;

trait WfIntervenantEtapeAwareTrait
{
    /**
     * description
     *
     * @var WfIntervenantEtape
     */
    private $serviceWfIntervenantEtape;

    /**
     *
     * @param WfIntervenantEtape $serviceWfIntervenantEtape
     * @return self
     */
    public function setServiceWfIntervenantEtape( WfIntervenantEtape $serviceWfIntervenantEtape )
    {
        $this->serviceWfIntervenantEtape = $serviceWfIntervenantEtape;
        return $this;
    }

    /**
     *
     * @return WfIntervenantEtape
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceWfIntervenantEtape()
    {
        if (empty($this->serviceWfIntervenantEtape)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationWfIntervenantEtape');
        }else{
            return $this->serviceWfIntervenantEtape;
        }
    }

}
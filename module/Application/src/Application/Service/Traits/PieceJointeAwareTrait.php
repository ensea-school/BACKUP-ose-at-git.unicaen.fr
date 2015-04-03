<?php

namespace Application\Service\Traits;

use Application\Service\PieceJointe;
use Common\Exception\RuntimeException;

trait PieceJointeAwareTrait
{
    /**
     * description
     *
     * @var PieceJointe
     */
    private $servicePieceJointe;

    /**
     *
     * @param PieceJointe $servicePieceJointe
     * @return self
     */
    public function setServicePieceJointe( PieceJointe $servicePieceJointe )
    {
        $this->servicePieceJointe = $servicePieceJointe;
        return $this;
    }

    /**
     *
     * @return PieceJointe
     * @throws \Common\Exception\RuntimeException
     */
    public function getServicePieceJointe()
    {
        if (empty($this->servicePieceJointe)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationPieceJointe');
        }else{
            return $this->servicePieceJointe;
        }
    }

}
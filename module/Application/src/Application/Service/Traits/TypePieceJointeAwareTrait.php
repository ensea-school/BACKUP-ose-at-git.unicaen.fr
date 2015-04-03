<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointe;
use Common\Exception\RuntimeException;

trait TypePieceJointeAwareTrait
{
    /**
     * description
     *
     * @var TypePieceJointe
     */
    private $serviceTypePieceJointe;

    /**
     *
     * @param TypePieceJointe $serviceTypePieceJointe
     * @return self
     */
    public function setServiceTypePieceJointe( TypePieceJointe $serviceTypePieceJointe )
    {
        $this->serviceTypePieceJointe = $serviceTypePieceJointe;
        return $this;
    }

    /**
     *
     * @return TypePieceJointe
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypePieceJointe()
    {
        if (empty($this->serviceTypePieceJointe)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypePieceJointe');
        }else{
            return $this->serviceTypePieceJointe;
        }
    }

}
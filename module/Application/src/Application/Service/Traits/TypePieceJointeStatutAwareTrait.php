<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointeStatut;
use Common\Exception\RuntimeException;

trait TypePieceJointeStatutAwareTrait
{
    /**
     * description
     *
     * @var TypePieceJointeStatut
     */
    private $serviceTypePieceJointeStatut;

    /**
     *
     * @param TypePieceJointeStatut $serviceTypePieceJointeStatut
     * @return self
     */
    public function setServiceTypePieceJointeStatut( TypePieceJointeStatut $serviceTypePieceJointeStatut )
    {
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;
        return $this;
    }

    /**
     *
     * @return TypePieceJointeStatut
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypePieceJointeStatut()
    {
        if (empty($this->serviceTypePieceJointeStatut)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypePieceJointeStatut');
        }else{
            return $this->serviceTypePieceJointeStatut;
        }
    }

}
<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointe;

/**
 * Description of TypePieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeAwareTrait
{
    /**
     * @var TypePieceJointe
     */
    private $serviceTypePieceJointe;



    /**
     * @param TypePieceJointe $serviceTypePieceJointe
     *
     * @return self
     */
    public function setServiceTypePieceJointe(TypePieceJointe $serviceTypePieceJointe)
    {
        $this->serviceTypePieceJointe = $serviceTypePieceJointe;

        return $this;
    }



    /**
     * @return TypePieceJointe
     */
    public function getServiceTypePieceJointe()
    {
        if (empty($this->serviceTypePieceJointe)) {
            $this->serviceTypePieceJointe = \Application::$container->get('ApplicationTypePieceJointe');
        }

        return $this->serviceTypePieceJointe;
    }
}
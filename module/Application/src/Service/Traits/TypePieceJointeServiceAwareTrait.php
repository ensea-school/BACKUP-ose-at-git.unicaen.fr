<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointeService;

/**
 * Description of TypePieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeServiceAwareTrait
{
    /**
     * @var TypePieceJointeService
     */
    private $serviceTypePieceJointe;



    /**
     * @param TypePieceJointeService $serviceTypePieceJointe
     *
     * @return self
     */
    public function setServiceTypePieceJointe(TypePieceJointeService $serviceTypePieceJointe)
    {
        $this->serviceTypePieceJointe = $serviceTypePieceJointe;

        return $this;
    }



    /**
     * @return TypePieceJointeService
     */
    public function getServiceTypePieceJointe()
    {
        if (empty($this->serviceTypePieceJointe)) {
            $this->serviceTypePieceJointe = \Application::$container->get(TypePieceJointeService::class);
        }

        return $this->serviceTypePieceJointe;
    }
}
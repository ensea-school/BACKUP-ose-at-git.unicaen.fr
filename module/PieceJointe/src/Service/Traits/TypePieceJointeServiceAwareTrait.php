<?php

namespace PieceJointe\Service\Traits;

use PieceJointe\Service\TypePieceJointeService;

/**
 * Description of TypePieceJointeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeServiceAwareTrait
{
    protected ?TypePieceJointeService $serviceTypePieceJointe = null;



    /**
     * @param TypePieceJointeService $serviceTypePieceJointe
     *
     * @return self
     */
    public function setServiceTypePieceJointe(?TypePieceJointeService $serviceTypePieceJointe)
    {
        $this->serviceTypePieceJointe = $serviceTypePieceJointe;

        return $this;
    }



    public function getServiceTypePieceJointe(): ?TypePieceJointeService
    {
        if (empty($this->serviceTypePieceJointe)) {
            $this->serviceTypePieceJointe = \Framework\Application\Application::getInstance()->container()->get(TypePieceJointeService::class);
        }

        return $this->serviceTypePieceJointe;
    }
}
<?php

namespace PieceJointe\Service\Traits;

use PieceJointe\Service\TypePieceJointeStatutService;

/**
 * Description of TypePieceJointeStatutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeStatutServiceAwareTrait
{
    protected ?TypePieceJointeStatutService $serviceTypePieceJointeStatut = null;



    /**
     * @param TypePieceJointeStatutService $serviceTypePieceJointeStatut
     *
     * @return self
     */
    public function setServiceTypePieceJointeStatut(?TypePieceJointeStatutService $serviceTypePieceJointeStatut)
    {
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;

        return $this;
    }



    public function getServiceTypePieceJointeStatut(): ?TypePieceJointeStatutService
    {
        if (empty($this->serviceTypePieceJointeStatut)) {
            $this->serviceTypePieceJointeStatut = \Framework\Application\Application::getInstance()->container()->get(TypePieceJointeStatutService::class);
        }

        return $this->serviceTypePieceJointeStatut;
    }
}
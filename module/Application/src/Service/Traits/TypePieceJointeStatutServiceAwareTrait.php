<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointeStatutService;

/**
 * Description of TypePieceJointeStatutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeStatutServiceAwareTrait
{
    protected ?TypePieceJointeStatutService $serviceTypePieceJointeStatut;



    /**
     * @param TypePieceJointeStatutService|null $serviceTypePieceJointeStatut
     *
     * @return self
     */
    public function setServiceTypePieceJointeStatut( ?TypePieceJointeStatutService $serviceTypePieceJointeStatut )
    {
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;

        return $this;
    }



    public function getServiceTypePieceJointeStatut(): ?TypePieceJointeStatutService
    {
        if (!$this->serviceTypePieceJointeStatut){
            $this->serviceTypePieceJointeStatut = \Application::$container->get(TypePieceJointeStatutService::class);
        }

        return $this->serviceTypePieceJointeStatut;
    }
}
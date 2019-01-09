<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointeStatutService;

/**
 * Description of TypePieceJointeStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeStatutServiceAwareTrait
{
    /**
     * @var TypePieceJointeStatutService
     */
    private $serviceTypePieceJointeStatut;



    /**
     * @param TypePieceJointeStatutService $serviceTypePieceJointeStatut
     *
     * @return self
     */
    public function setServiceTypePieceJointeStatut(TypePieceJointeStatutService $serviceTypePieceJointeStatut)
    {
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;

        return $this;
    }



    /**
     * @return TypePieceJointeStatutService
     */
    public function getServiceTypePieceJointeStatut()
    {
        if (empty($this->serviceTypePieceJointeStatut)) {
            $this->serviceTypePieceJointeStatut = \Application::$container->get(TypePieceJointeStatutService::class);
        }

        return $this->serviceTypePieceJointeStatut;
    }
}
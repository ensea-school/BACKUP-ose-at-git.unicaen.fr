<?php

namespace Application\Service\Interfaces;

use Application\Service\TypePieceJointeStatutService;
use RuntimeException;

/**
 * Description of TypePieceJointeStatutAwareInterface
 *
 * @author UnicaenCode
 */
interface TypePieceJointeStatutAwareInterface
{
    /**
     * @param TypePieceJointeStatutService $serviceTypePieceJointeStatut
     *
     * @return self
     */
    public function setServiceTypePieceJointeStatut(TypePieceJointeStatutService $serviceTypePieceJointeStatut );



    /**
     * @return TypePieceJointeStatutAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypePieceJointeStatut();
}
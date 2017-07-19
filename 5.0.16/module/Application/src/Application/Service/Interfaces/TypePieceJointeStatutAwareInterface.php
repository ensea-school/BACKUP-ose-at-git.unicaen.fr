<?php

namespace Application\Service\Interfaces;

use Application\Service\TypePieceJointeStatut;
use RuntimeException;

/**
 * Description of TypePieceJointeStatutAwareInterface
 *
 * @author UnicaenCode
 */
interface TypePieceJointeStatutAwareInterface
{
    /**
     * @param TypePieceJointeStatut $serviceTypePieceJointeStatut
     * @return self
     */
    public function setServiceTypePieceJointeStatut( TypePieceJointeStatut $serviceTypePieceJointeStatut );



    /**
     * @return TypePieceJointeStatutAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypePieceJointeStatut();
}
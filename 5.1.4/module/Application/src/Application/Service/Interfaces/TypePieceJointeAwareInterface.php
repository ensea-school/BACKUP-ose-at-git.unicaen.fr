<?php

namespace Application\Service\Interfaces;

use Application\Service\TypePieceJointe;
use RuntimeException;

/**
 * Description of TypePieceJointeAwareInterface
 *
 * @author UnicaenCode
 */
interface TypePieceJointeAwareInterface
{
    /**
     * @param TypePieceJointe $serviceTypePieceJointe
     * @return self
     */
    public function setServiceTypePieceJointe( TypePieceJointe $serviceTypePieceJointe );



    /**
     * @return TypePieceJointeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypePieceJointe();
}
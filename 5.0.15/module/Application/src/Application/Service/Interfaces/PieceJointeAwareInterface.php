<?php

namespace Application\Service\Interfaces;

use Application\Service\PieceJointe;
use RuntimeException;

/**
 * Description of PieceJointeAwareInterface
 *
 * @author UnicaenCode
 */
interface PieceJointeAwareInterface
{
    /**
     * @param PieceJointe $servicePieceJointe
     * @return self
     */
    public function setServicePieceJointe( PieceJointe $servicePieceJointe );



    /**
     * @return PieceJointeAwareInterface
     * @throws RuntimeException
     */
    public function getServicePieceJointe();
}
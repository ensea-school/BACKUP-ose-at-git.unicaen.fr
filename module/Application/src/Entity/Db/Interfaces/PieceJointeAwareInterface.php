<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\PieceJointe;

/**
 * Description of PieceJointeAwareInterface
 *
 * @author UnicaenCode
 */
interface PieceJointeAwareInterface
{
    /**
     * @param PieceJointe $pieceJointe
     * @return self
     */
    public function setPieceJointe( PieceJointe $pieceJointe = null );



    /**
     * @return PieceJointe
     */
    public function getPieceJointe();
}
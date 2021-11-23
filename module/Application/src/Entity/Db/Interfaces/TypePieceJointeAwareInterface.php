<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypePieceJointe;

/**
 * Description of TypePieceJointeAwareInterface
 *
 * @author UnicaenCode
 */
interface TypePieceJointeAwareInterface
{
    /**
     * @param TypePieceJointe $typePieceJointe
     * @return self
     */
    public function setTypePieceJointe( TypePieceJointe $typePieceJointe = null );



    /**
     * @return TypePieceJointe
     */
    public function getTypePieceJointe();
}
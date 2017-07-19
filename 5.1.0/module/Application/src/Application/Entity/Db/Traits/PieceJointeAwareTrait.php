<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\PieceJointe;

/**
 * Description of PieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeAwareTrait
{
    /**
     * @var PieceJointe
     */
    private $pieceJointe;





    /**
     * @param PieceJointe $pieceJointe
     * @return self
     */
    public function setPieceJointe( PieceJointe $pieceJointe = null )
    {
        $this->pieceJointe = $pieceJointe;
        return $this;
    }



    /**
     * @return PieceJointe
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
    }
}
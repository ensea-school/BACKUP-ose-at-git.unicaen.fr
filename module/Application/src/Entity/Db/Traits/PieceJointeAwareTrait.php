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
    protected ?PieceJointe $pieceJointe;



    /**
     * @param PieceJointe|null $pieceJointe
     *
     * @return self
     */
    public function setPieceJointe( ?PieceJointe $pieceJointe )
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }



    public function getPieceJointe(): ?PieceJointe
    {
        return $this->pieceJointe;
    }
}
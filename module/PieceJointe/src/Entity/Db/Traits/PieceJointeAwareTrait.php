<?php

namespace PieceJointe\Entity\Db\Traits;

use PieceJointe\Entity\Db\PieceJointe;

/**
 * Description of PieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeAwareTrait
{
    protected ?PieceJointe $pieceJointe = null;



    /**
     * @param PieceJointe $pieceJointe
     *
     * @return self
     */
    public function setPieceJointe(?PieceJointe $pieceJointe)
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }



    public function getPieceJointe(): ?PieceJointe
    {
        return $this->pieceJointe;
    }
}
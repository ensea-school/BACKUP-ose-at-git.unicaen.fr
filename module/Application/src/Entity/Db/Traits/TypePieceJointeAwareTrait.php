<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypePieceJointe;

/**
 * Description of TypePieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeAwareTrait
{
    protected ?TypePieceJointe $typePieceJointe = null;



    /**
     * @param TypePieceJointe $typePieceJointe
     *
     * @return self
     */
    public function setTypePieceJointe( ?TypePieceJointe $typePieceJointe )
    {
        $this->typePieceJointe = $typePieceJointe;

        return $this;
    }



    public function getTypePieceJointe(): ?TypePieceJointe
    {
        return $this->typePieceJointe;
    }
}
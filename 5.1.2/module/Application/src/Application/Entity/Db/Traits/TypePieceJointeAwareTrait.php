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
    /**
     * @var TypePieceJointe
     */
    private $typePieceJointe;





    /**
     * @param TypePieceJointe $typePieceJointe
     * @return self
     */
    public function setTypePieceJointe( TypePieceJointe $typePieceJointe = null )
    {
        $this->typePieceJointe = $typePieceJointe;
        return $this;
    }



    /**
     * @return TypePieceJointe
     */
    public function getTypePieceJointe()
    {
        return $this->typePieceJointe;
    }
}
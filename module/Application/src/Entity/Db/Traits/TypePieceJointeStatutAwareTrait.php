<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypePieceJointeStatut;

/**
 * Description of TypePieceJointeStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeStatutAwareTrait
{
    /**
     * @var TypePieceJointeStatut
     */
    private $typePieceJointeStatut;





    /**
     * @param TypePieceJointeStatut $typePieceJointeStatut
     * @return self
     */
    public function setTypePieceJointeStatut( TypePieceJointeStatut $typePieceJointeStatut = null )
    {
        $this->typePieceJointeStatut = $typePieceJointeStatut;
        return $this;
    }



    /**
     * @return TypePieceJointeStatut
     */
    public function getTypePieceJointeStatut()
    {
        return $this->typePieceJointeStatut;
    }
}
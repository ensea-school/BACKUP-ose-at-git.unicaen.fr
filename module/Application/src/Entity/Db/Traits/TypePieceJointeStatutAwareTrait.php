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
    protected ?TypePieceJointeStatut $typePieceJointeStatut;



    /**
     * @param TypePieceJointeStatut|null $typePieceJointeStatut
     *
     * @return self
     */
    public function setTypePieceJointeStatut( ?TypePieceJointeStatut $typePieceJointeStatut )
    {
        $this->typePieceJointeStatut = $typePieceJointeStatut;

        return $this;
    }



    public function getTypePieceJointeStatut(): ?TypePieceJointeStatut
    {
        return $this->typePieceJointeStatut;
    }
}
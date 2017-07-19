<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypePieceJointeStatut;

/**
 * Description of TypePieceJointeStatutAwareInterface
 *
 * @author UnicaenCode
 */
interface TypePieceJointeStatutAwareInterface
{
    /**
     * @param TypePieceJointeStatut $typePieceJointeStatut
     * @return self
     */
    public function setTypePieceJointeStatut( TypePieceJointeStatut $typePieceJointeStatut = null );



    /**
     * @return TypePieceJointeStatut
     */
    public function getTypePieceJointeStatut();
}
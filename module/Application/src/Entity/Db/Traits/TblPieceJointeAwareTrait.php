<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblPieceJointe;

/**
 * Description of TblPieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeAwareTrait
{
    protected ?TblPieceJointe $tblPieceJointe;



    /**
     * @param TblPieceJointe|null $tblPieceJointe
     *
     * @return self
     */
    public function setTblPieceJointe( ?TblPieceJointe $tblPieceJointe )
    {
        $this->tblPieceJointe = $tblPieceJointe;

        return $this;
    }



    public function getTblPieceJointe(): ?TblPieceJointe
    {
        return $this->tblPieceJointe;
    }
}
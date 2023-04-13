<?php

namespace PieceJointe\Entity\Db\Traits;

use PieceJointe\Entity\Db\TblPieceJointe;

/**
 * Description of TblPieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeAwareTrait
{
    protected ?TblPieceJointe $tblPieceJointe = null;



    /**
     * @param TblPieceJointe $tblPieceJointe
     *
     * @return self
     */
    public function setTblPieceJointe(?TblPieceJointe $tblPieceJointe)
    {
        $this->tblPieceJointe = $tblPieceJointe;

        return $this;
    }



    public function getTblPieceJointe(): ?TblPieceJointe
    {
        return $this->tblPieceJointe;
    }
}
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
    /**
     * @var TblPieceJointe
     */
    private $tblPieceJointe;





    /**
     * @param TblPieceJointe $tblPieceJointe
     * @return self
     */
    public function setTblPieceJointe( TblPieceJointe $tblPieceJointe = null )
    {
        $this->tblPieceJointe = $tblPieceJointe;
        return $this;
    }



    /**
     * @return TblPieceJointe
     */
    public function getTblPieceJointe()
    {
        return $this->tblPieceJointe;
    }
}
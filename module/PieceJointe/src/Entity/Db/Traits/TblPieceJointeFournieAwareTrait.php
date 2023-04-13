<?php

namespace PieceJointe\Entity\Db\Traits;

use PieceJointe\Entity\Db\TblPieceJointeFournie;

/**
 * Description of TblPieceJointeFournieAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeFournieAwareTrait
{
    protected ?TblPieceJointeFournie $tblPieceJointeFournie = null;



    /**
     * @param TblPieceJointeFournie $tblPieceJointeFournie
     *
     * @return self
     */
    public function setTblPieceJointeFournie(?TblPieceJointeFournie $tblPieceJointeFournie)
    {
        $this->tblPieceJointeFournie = $tblPieceJointeFournie;

        return $this;
    }



    public function getTblPieceJointeFournie(): ?TblPieceJointeFournie
    {
        return $this->tblPieceJointeFournie;
    }
}
<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblPieceJointeFournie;

/**
 * Description of TblPieceJointeFournieAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeFournieAwareTrait
{
    protected ?TblPieceJointeFournie $tblPieceJointeFournie;



    /**
     * @param TblPieceJointeFournie|null $tblPieceJointeFournie
     *
     * @return self
     */
    public function setTblPieceJointeFournie( ?TblPieceJointeFournie $tblPieceJointeFournie )
    {
        $this->tblPieceJointeFournie = $tblPieceJointeFournie;

        return $this;
    }



    public function getTblPieceJointeFournie(): ?TblPieceJointeFournie
    {
        return $this->tblPieceJointeFournie;
    }
}
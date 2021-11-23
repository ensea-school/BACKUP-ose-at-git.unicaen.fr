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
    /**
     * @var TblPieceJointeFournie
     */
    private $tblPieceJointeFournie;





    /**
     * @param TblPieceJointeFournie $tblPieceJointeFournie
     * @return self
     */
    public function setTblPieceJointeFournie( TblPieceJointeFournie $tblPieceJointeFournie = null )
    {
        $this->tblPieceJointeFournie = $tblPieceJointeFournie;
        return $this;
    }



    /**
     * @return TblPieceJointeFournie
     */
    public function getTblPieceJointeFournie()
    {
        return $this->tblPieceJointeFournie;
    }
}
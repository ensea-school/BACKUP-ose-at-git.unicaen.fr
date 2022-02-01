<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblPieceJointeDemande;

/**
 * Description of TblPieceJointeDemandeAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeDemandeAwareTrait
{
    protected ?TblPieceJointeDemande $tblPieceJointeDemande = null;



    /**
     * @param TblPieceJointeDemande $tblPieceJointeDemande
     *
     * @return self
     */
    public function setTblPieceJointeDemande( ?TblPieceJointeDemande $tblPieceJointeDemande )
    {
        $this->tblPieceJointeDemande = $tblPieceJointeDemande;

        return $this;
    }



    public function getTblPieceJointeDemande(): ?TblPieceJointeDemande
    {
        return $this->tblPieceJointeDemande;
    }
}
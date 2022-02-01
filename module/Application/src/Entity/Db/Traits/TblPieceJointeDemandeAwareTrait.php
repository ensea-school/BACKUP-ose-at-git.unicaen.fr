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
    protected ?TblPieceJointeDemande $tblPieceJointeDemande;



    /**
     * @param TblPieceJointeDemande|null $tblPieceJointeDemande
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
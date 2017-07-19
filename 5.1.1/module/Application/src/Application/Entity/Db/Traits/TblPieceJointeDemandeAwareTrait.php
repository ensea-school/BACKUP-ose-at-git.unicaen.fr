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
    /**
     * @var TblPieceJointeDemande
     */
    private $tblPieceJointeDemande;





    /**
     * @param TblPieceJointeDemande $tblPieceJointeDemande
     * @return self
     */
    public function setTblPieceJointeDemande( TblPieceJointeDemande $tblPieceJointeDemande = null )
    {
        $this->tblPieceJointeDemande = $tblPieceJointeDemande;
        return $this;
    }



    /**
     * @return TblPieceJointeDemande
     */
    public function getTblPieceJointeDemande()
    {
        return $this->tblPieceJointeDemande;
    }
}
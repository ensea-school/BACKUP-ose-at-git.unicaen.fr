<?php

namespace Paiement\Entity\Db;

/**
 * Description of TblPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPaiementAwareTrait
{
    protected ?TblPaiement $tblPaiement = null;



    /**
     * @param TblPaiement $tblPaiement
     *
     * @return self
     */
    public function setTblPaiement( ?TblPaiement $tblPaiement )
    {
        $this->tblPaiement = $tblPaiement;

        return $this;
    }



    public function getTblPaiement(): ?TblPaiement
    {
        return $this->tblPaiement;
    }
}
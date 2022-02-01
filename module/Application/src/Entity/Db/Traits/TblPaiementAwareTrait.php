<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblPaiement;

/**
 * Description of TblPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPaiementAwareTrait
{
    protected ?TblPaiement $tblPaiement;



    /**
     * @param TblPaiement|null $tblPaiement
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
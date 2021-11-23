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
    /**
     * @var TblPaiement
     */
    private $tblPaiement;





    /**
     * @param TblPaiement $tblPaiement
     * @return self
     */
    public function setTblPaiement( TblPaiement $tblPaiement = null )
    {
        $this->tblPaiement = $tblPaiement;
        return $this;
    }



    /**
     * @return TblPaiement
     */
    public function getTblPaiement()
    {
        return $this->tblPaiement;
    }
}
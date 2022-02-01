<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblServiceSaisie;

/**
 * Description of TblServiceSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceSaisieAwareTrait
{
    protected ?TblServiceSaisie $tblServiceSaisie = null;



    /**
     * @param TblServiceSaisie $tblServiceSaisie
     *
     * @return self
     */
    public function setTblServiceSaisie( TblServiceSaisie $tblServiceSaisie )
    {
        $this->tblServiceSaisie = $tblServiceSaisie;

        return $this;
    }



    public function getTblServiceSaisie(): ?TblServiceSaisie
    {
        return $this->tblServiceSaisie;
    }
}
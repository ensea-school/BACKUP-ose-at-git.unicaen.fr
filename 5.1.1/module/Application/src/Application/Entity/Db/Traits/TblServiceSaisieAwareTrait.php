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
    /**
     * @var TblServiceSaisie
     */
    private $tblServiceSaisie;





    /**
     * @param TblServiceSaisie $tblServiceSaisie
     * @return self
     */
    public function setTblServiceSaisie( TblServiceSaisie $tblServiceSaisie = null )
    {
        $this->tblServiceSaisie = $tblServiceSaisie;
        return $this;
    }



    /**
     * @return TblServiceSaisie
     */
    public function getTblServiceSaisie()
    {
        return $this->tblServiceSaisie;
    }
}
<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblAgrement;

/**
 * Description of TblAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TblAgrementAwareTrait
{
    /**
     * @var TblAgrement
     */
    private $tblAgrement;





    /**
     * @param TblAgrement $tblAgrement
     * @return self
     */
    public function setTblAgrement( TblAgrement $tblAgrement = null )
    {
        $this->tblAgrement = $tblAgrement;
        return $this;
    }



    /**
     * @return TblAgrement
     */
    public function getTblAgrement()
    {
        return $this->tblAgrement;
    }
}
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
    protected ?TblAgrement $tblAgrement;



    /**
     * @param TblAgrement|null $tblAgrement
     *
     * @return self
     */
    public function setTblAgrement( ?TblAgrement $tblAgrement )
    {
        $this->tblAgrement = $tblAgrement;

        return $this;
    }



    public function getTblAgrement(): ?TblAgrement
    {
        return $this->tblAgrement;
    }
}
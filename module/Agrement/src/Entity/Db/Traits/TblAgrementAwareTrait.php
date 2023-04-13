<?php

namespace Agrement\Entity\Db\Traits;

use Agrement\Entity\Db\TblAgrement;

/**
 * Description of TblAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TblAgrementAwareTrait
{
    protected ?TblAgrement $tblAgrement = null;



    /**
     * @param TblAgrement $tblAgrement
     *
     * @return self
     */
    public function setTblAgrement(?TblAgrement $tblAgrement)
    {
        $this->tblAgrement = $tblAgrement;

        return $this;
    }



    public function getTblAgrement(): ?TblAgrement
    {
        return $this->tblAgrement;
    }
}
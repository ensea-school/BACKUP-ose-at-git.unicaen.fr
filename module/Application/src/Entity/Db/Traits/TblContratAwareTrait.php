<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblContrat;

/**
 * Description of TblContratAwareTrait
 *
 * @author UnicaenCode
 */
trait TblContratAwareTrait
{
    protected ?TblContrat $tblContrat = null;



    /**
     * @param TblContrat $tblContrat
     *
     * @return self
     */
    public function setTblContrat( ?TblContrat $tblContrat )
    {
        $this->tblContrat = $tblContrat;

        return $this;
    }



    public function getTblContrat(): ?TblContrat
    {
        return $this->tblContrat;
    }
}
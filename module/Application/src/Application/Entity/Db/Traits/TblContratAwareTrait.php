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
    /**
     * @var TblContrat
     */
    private $tblContrat;





    /**
     * @param TblContrat $tblContrat
     * @return self
     */
    public function setTblContrat( TblContrat $tblContrat = null )
    {
        $this->tblContrat = $tblContrat;
        return $this;
    }



    /**
     * @return TblContrat
     */
    public function getTblContrat()
    {
        return $this->tblContrat;
    }
}
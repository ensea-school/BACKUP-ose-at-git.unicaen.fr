<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblClotureRealise;

/**
 * Description of TblClotureRealiseAwareTrait
 *
 * @author UnicaenCode
 */
trait TblClotureRealiseAwareTrait
{
    /**
     * @var TblClotureRealise
     */
    private $tblClotureRealise;





    /**
     * @param TblClotureRealise $tblClotureRealise
     * @return self
     */
    public function setTblClotureRealise( TblClotureRealise $tblClotureRealise = null )
    {
        $this->tblClotureRealise = $tblClotureRealise;
        return $this;
    }



    /**
     * @return TblClotureRealise
     */
    public function getTblClotureRealise()
    {
        return $this->tblClotureRealise;
    }
}
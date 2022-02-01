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
    protected ?TblClotureRealise $tblClotureRealise;



    /**
     * @param TblClotureRealise|null $tblClotureRealise
     *
     * @return self
     */
    public function setTblClotureRealise( ?TblClotureRealise $tblClotureRealise )
    {
        $this->tblClotureRealise = $tblClotureRealise;

        return $this;
    }



    public function getTblClotureRealise(): ?TblClotureRealise
    {
        return $this->tblClotureRealise;
    }
}
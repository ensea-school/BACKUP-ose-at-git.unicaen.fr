<?php

namespace Service\Entity\Db;

/**
 * Description of TblClotureRealiseAwareTrait
 *
 * @author UnicaenCode
 */
trait TblClotureRealiseAwareTrait
{
    protected ?TblClotureRealise $tblClotureRealise = null;



    /**
     * @param TblClotureRealise $tblClotureRealise
     *
     * @return self
     */
    public function setTblClotureRealise(?TblClotureRealise $tblClotureRealise)
    {
        $this->tblClotureRealise = $tblClotureRealise;

        return $this;
    }



    public function getTblClotureRealise(): ?TblClotureRealise
    {
        return $this->tblClotureRealise;
    }
}